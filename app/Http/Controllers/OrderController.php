<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TambalBan;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. Tampilkan Formulir Pemesanan
    public function create($id)
    {
        $bengkel = TambalBan::findOrFail($id);
        return view('booking.create', compact('bengkel'));
    }

    // 2. Simpan Pesanan ke Database
    public function store(Request $request)
    {
        $request->validate([
            'tambal_ban_id' => 'required',
            'nama_pemesan' => 'required',
            'nomer_telepon' => 'required',
            'alamat_lengkap' => 'required',
            'jenis_kendaraan' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        Order::create([
            'user_id' => Auth::id(),
            'tambal_ban_id' => $request->tambal_ban_id,
            'nama_pemesan' => $request->nama_pemesan,
            'nomer_telepon' => $request->nomer_telepon,
            'alamat_lengkap' => $request->alamat_lengkap,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'keluhan' => $request->keluhan,
            'status' => 'pending',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('booking.history')->with('success', 'Pesanan berhasil dibuat! Mohon tunggu konfirmasi bengkel.');
    }

    // 3. Lihat Riwayat Pesanan User
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->with('tambalBan')
                        ->latest()
                        ->get();

        return view('booking.history', compact('orders'));
    }

    public function show($id)
    {
        // Ambil data order beserta data bengkelnya
        $order = Order::with('tambalBan')->findOrFail($id);

        // Keamanan: Pastikan yang melihat adalah pemilik pesanan
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        return view('booking.show', compact('order'));
    }

    // --- KHUSUS ADMIN ---

    // 5. Tampilkan Semua Pesanan Masuk
    public function adminIndex()
    {
        // Ambil semua order, urutkan dari yang terbaru
        // Kita load juga relasi user dan tambalBan agar tidak N+1 Problem
        $orders = Order::with(['user', 'tambalBan'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    // 6. Update Status Pesanan (Proses / Selesai / Batal)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,proses,selesai,batal'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Method Baru: Detail Pesanan Admin
    public function adminShow($id)
    {
        // Load data order beserta relasi user, bengkel, dan ownernya
        $order = Order::with(['user', 'tambalBan.owner'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    // Method untuk User membatalkan pesanan
    public function cancelOrder($id)
    {
        // Cari order milik user yang sedang login
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Validasi: Hanya bisa batal jika status masih 'pending'
        if($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        $order->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

}
