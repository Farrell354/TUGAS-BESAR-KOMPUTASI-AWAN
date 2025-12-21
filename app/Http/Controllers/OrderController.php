<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TambalBan;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

// --- LIBRARY MIDTRANS ---
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction; // <--- Wajib ada untuk Cek Status

class OrderController extends Controller
{
    // 1. Tampilkan Formulir Pemesanan
    public function create($id)
    {
        $bengkel = TambalBan::findOrFail($id);
        return view('booking.create', compact('bengkel'));
    }

    // 2. Simpan Pesanan (Logika Jarak, Harga, & Midtrans)
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'tambal_ban_id' => 'required',
            'nama_pemesan' => 'required',
            'nomer_telepon' => 'required',
            'alamat_lengkap' => 'required',
            'jenis_kendaraan' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'metode_pembayaran' => 'required|in:cod,transfer', 
        ]);

        // B. Hitung Jarak & Harga
        $bengkel = TambalBan::findOrFail($request->tambal_ban_id);
        
        $jarak_km = $this->calculateDistance(
            $request->latitude, 
            $request->longitude, 
            $bengkel->latitude, 
            $bengkel->longitude
        );

        $biaya_jasa = 0;

        if ($jarak_km > 10) {
            return back()->with('error', 'Maaf, lokasi Anda terlalu jauh (' . number_format($jarak_km, 1) . ' km). Maksimal 10 km.');
        }

       if ($request->jenis_kendaraan == 'mobil') {
            // --- HARGA MOBIL ---
            if ($jarak_km <= 5) {
                $biaya_jasa = $bengkel->harga_mobil_dekat; 
            } else {
                $biaya_jasa = $bengkel->harga_mobil_jauh; 
            }
        } else {
            // --- HARGA MOTOR ---
            if ($jarak_km <= 5) {
                $biaya_jasa = $bengkel->harga_motor_dekat; 
            } else {
                $biaya_jasa = $bengkel->harga_motor_jauh; 
            }
        }

        // C. Simpan ke Database
        // Gunakan time() agar Kode Order unik dan tidak bentrok di Midtrans
        $kodeUnik = 'TRX-' . time() . rand(100, 999);

        $order = Order::create([
            'kode_order' => $kodeUnik,
            'user_id' => Auth::id(),
            'tambal_ban_id' => $request->tambal_ban_id,
            'nama_pemesan' => $request->nama_pemesan,
            'nomer_telepon' => $request->nomer_telepon,
            'alamat_lengkap' => $request->alamat_lengkap,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'keluhan' => $request->keluhan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => $biaya_jasa,
            'payment_status' => 'unpaid',
        ]);

        // D. Request Token ke Midtrans (Jika Transfer)
        if ($request->metode_pembayaran == 'transfer') {
            
            // Konfigurasi Midtrans
            $this->configureMidtrans();

            $params = [
                'transaction_details' => [
                    'order_id' => $order->id, // ID Database kita
                    'gross_amount' => (int) $order->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $request->nama_pemesan,
                    'email' => Auth::user()->email,
                    'phone' => $request->nomer_telepon,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return back()->with('error', 'Midtrans Error: ' . $e->getMessage());
            }
        }

        return redirect()->route('booking.show', $order->id)
            ->with('success', 'Pesanan dibuat! Estimasi Jarak: ' . number_format($jarak_km, 1) . ' km');
    }

    // 3. Detail Pesanan User (DENGAN FITUR AUTO-CHECK LOCALHOST)
    public function show($id)
    {
        $order = Order::with('tambalBan')->findOrFail($id);

        // Keamanan: Pastikan user yang login adalah pemilik pesanan
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // --- MAGIS: AUTO-UPDATE STATUS UTK LOCALHOST ---
        if ($order->metode_pembayaran == 'transfer' && $order->payment_status == 'unpaid') {
            $this->configureMidtrans(); // Load config
            try {
                // Cek status langsung ke server Midtrans
                $status = Transaction::status($order->id); 
                
                // Jika status di Midtrans sudah sukses, update database kita
                if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                    $order->update(['payment_status' => 'paid']);
                } else if ($status->transaction_status == 'expire') {
                    $order->update(['payment_status' => 'expired']);
                } else if ($status->transaction_status == 'cancel') {
                    $order->update(['payment_status' => 'failed']);
                }
            } catch (\Exception $e) {
            }
        }


        return view('booking.show', compact('order'));
    }

    // 4. Admin/Owner: Detail Pesanan (Juga dikasih Auto-Check biar Owner update real-time)
    public function adminShow($id)
    {
        $order = Order::with(['user', 'tambalBan.owner'])->findOrFail($id);

        // --- AUTO-UPDATE STATUS UTK OWNER JUGA ---
        if ($order->metode_pembayaran == 'transfer' && $order->payment_status == 'unpaid') {
            $this->configureMidtrans();
            try {
                $status = Transaction::status($order->id);
                if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                    $order->update(['payment_status' => 'paid']);
                }
            } catch (\Exception $e) {}
        }
        // -----------------------------------------

        return view('admin.orders.show', compact('order')); // Sesuaikan jika view owner beda folder
    }

    // 5. Riwayat Pesanan
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->with('tambalBan')
                        ->latest()
                        ->get();

        return view('booking.history', compact('orders'));
    }

    // 6. Admin Index
    public function adminIndex()
    {
        $orders = Order::with(['user', 'tambalBan'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // 7. Update Status Pesanan (Terima/Tolak/Selesai)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Logika Update
        if ($request->has('action')) {
            // Logic khusus untuk tombol Terima/Tolak Owner
            if ($request->action == 'accept') {
                $order->update(['status' => 'proses']);
            } elseif ($request->action == 'reject') {
                $order->update(['status' => 'batal', 'alasan_batal' => $request->alasan ?? 'Ditolak Bengkel']);
            } elseif ($request->action == 'finish') {
                $order->update(['status' => 'selesai']);
                
                // Jika COD, otomatis set Lunas saat selesai
                if($order->metode_pembayaran == 'cod') {
                    $order->update(['payment_status' => 'paid']);
                }
            }
        } else {
            // Logic standard update status via select/dropdown
            $data = ['status' => $request->status];
            if ($request->status == 'batal' && $request->alasan_batal) {
                $data['alasan_batal'] = $request->alasan_batal;
            }
            $order->update($data);
        }

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    // 8. User Cancel
    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if($order->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses, tidak bisa dibatalkan.');
        }

        $order->update(['status' => 'batal', 'alasan_batal' => 'Dibatalkan oleh User']);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // --- HELPER FUNCTIONS ---

    // Konfigurasi Midtrans
    private function configureMidtrans() {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    // Hitung Jarak (Haversine)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}