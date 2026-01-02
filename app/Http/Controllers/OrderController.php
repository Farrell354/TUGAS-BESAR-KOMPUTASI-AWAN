<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TambalBan;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// --- LIBRARY MIDTRANS ---
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class OrderController extends Controller
{
    // 1. Tampilkan Formulir Pemesanan
    public function create($id)
    {
        $bengkel = TambalBan::findOrFail($id);
        return view('booking.create', compact('bengkel'));
    }

    // 2. Simpan Pesanan (Logika Jarak, Harga Dinamis, Foto & Midtrans)
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'tambal_ban_id' => 'required',
            'nama_pemesan' => 'required',
            'nomer_telepon' => 'required',
            'alamat_lengkap' => 'required',
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'latitude' => 'required',
            'longitude' => 'required',
            'metode_pembayaran' => 'required|in:cod,transfer',
            // Validasi Foto (Maks 5MB)
            'foto_ban' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // B. Hitung Jarak
        $bengkel = TambalBan::findOrFail($request->tambal_ban_id);

        $jarak_km = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $bengkel->latitude,
            $bengkel->longitude
        );

        // Cek Jarak Maksimal
        if ($jarak_km > 10) {
            return back()->with('error', 'Maaf, lokasi Anda terlalu jauh (' . number_format($jarak_km, 1) . ' km). Maksimal 10 km.');
        }

        // C. Tentukan Harga Dinamis (Dari Database Bengkel)
        $biaya_jasa = 0;

        if ($request->jenis_kendaraan == 'mobil') {
            // --- HARGA MOBIL ---
            // Jika data harga di DB null/0, gunakan default 35rb/50rb (Fallback)
            $hargaDekat = $bengkel->harga_mobil_dekat ?? 35000;
            $hargaJauh = $bengkel->harga_mobil_jauh ?? 50000;

            $biaya_jasa = ($jarak_km <= 5) ? $hargaDekat : $hargaJauh;

        } else {
            // --- HARGA MOTOR ---
            // Jika data harga di DB null/0, gunakan default 20rb/35rb (Fallback)
            $hargaDekat = $bengkel->harga_motor_dekat ?? 20000;
            $hargaJauh = $bengkel->harga_motor_jauh ?? 35000;

            $biaya_jasa = ($jarak_km <= 5) ? $hargaDekat : $hargaJauh;
        }

        // D. Upload Foto (Jika Ada)
        $pathFoto = null;
        if ($request->hasFile('foto_ban')) {
            $pathFoto = $request->file('foto_ban')->store('order_images', 'public');
        }

        // E. Simpan ke Database
        // Gunakan time() agar Kode Order unik
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
            'foto_ban' => $pathFoto, // Simpan path foto
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => $biaya_jasa,
            'payment_status' => 'unpaid',
        ]);

        // F. Request Token ke Midtrans (Jika Transfer)
        if ($request->metode_pembayaran == 'transfer') {

            $this->configureMidtrans();

            $params = [
                'transaction_details' => [
                    'order_id' => $order->kode_order, // Pastikan ID Order benar
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
                return redirect()->route('booking.show', $order->id)
                    ->with('error', 'Pesanan berhasil dibuat, TETAPI Gagal memuat pembayaran Midtrans. Error: ' . $e->getMessage());
            }
        }

        // Redirect Sukses Normal
        return redirect()->route('booking.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Estimasi Jarak: ' . number_format($jarak_km, 1) . ' km');
    }

    // 3. Detail Pesanan User (DENGAN AUTO-UPDATE LOCALHOST)
    public function show($id)
    {
        $order = Order::with('tambalBan')->findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        // --- CEK STATUS MIDTRANS (Solusi Localhost) ---
        if ($order->metode_pembayaran == 'transfer' && $order->payment_status == 'unpaid') {
            $this->checkMidtransStatus($order);
        }
        // ---------------------------------------------

        return view('booking.show', compact('order'));
    }

    // 4. Detail Pesanan Admin/Owner (DENGAN AUTO-UPDATE)
    public function adminShow($id)
    {
        $order = Order::with(['user', 'tambalBan.owner'])->findOrFail($id);

        // --- CEK STATUS MIDTRANS UTK OWNER ---
        if ($order->metode_pembayaran == 'transfer' && $order->payment_status == 'unpaid') {
            $this->checkMidtransStatus($order);
        }
        // -------------------------------------

        return view('admin.orders.show', compact('order'));
    }

    // 5. Riwayat Pesanan User
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('tambalBan')
            ->latest()
            ->get();

        return view('booking.history', compact('orders'));
    }

    // 6. List Pesanan Admin
    public function adminIndex()
    {
        $orders = Order::with(['user', 'tambalBan'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // 7. Update Status Pesanan (Terima/Tolak/Selesai)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($request->has('action')) {
            // Logic Tombol Cepat (Owner)
            if ($request->action == 'accept') {
                $order->update(['status' => 'proses']);
            } elseif ($request->action == 'reject') {
                $order->update(['status' => 'batal', 'alasan_batal' => $request->alasan ?? 'Ditolak Bengkel']);
            } elseif ($request->action == 'finish') {
                $order->update(['status' => 'selesai']);
                // Jika COD, otomatis set Lunas saat selesai
                if ($order->metode_pembayaran == 'cod') {
                    $order->update(['payment_status' => 'paid']);
                }
            }
        } else {
            // Logic Dropdown Admin
            $data = ['status' => $request->status];
            if ($request->status == 'batal' && $request->alasan_batal) {
                $data['alasan_batal'] = $request->alasan_batal;
            }
            $order->update($data);
        }

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    // 8. User Cancel Pesanan
    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses, tidak bisa dibatalkan.');
        }

        $order->update(['status' => 'batal', 'alasan_batal' => 'Dibatalkan oleh User']);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // --- HELPER FUNCTIONS ---

    // Konfigurasi Midtrans
    private function configureMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    // Logika Cek Status Midtrans (Reusable)
    private function checkMidtransStatus($order)
    {
        $this->configureMidtrans();
        try {
            // Cek ke server Midtrans
            $rawStatus = Transaction::status($order->kode_order);

            // Kita paksa ubah jadi Object agar ($status->transaction_status) bisa jalan
            $status = (object) $rawStatus; 

            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->update(['payment_status' => 'paid']);
            } else if ($status->transaction_status == 'expire') {
                $order->update(['payment_status' => 'expired']);
            } else if ($status->transaction_status == 'cancel') {
                $order->update(['payment_status' => 'failed']);
            }
        } catch (\Exception $e) {
            // Abaikan error jika transaksi belum dibayar sama sekali
        }
    }

    // Hitung Jarak (Haversine Formula)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
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