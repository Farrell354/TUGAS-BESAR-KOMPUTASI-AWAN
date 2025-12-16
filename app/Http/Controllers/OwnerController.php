<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TambalBan;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    public function index()
    {
        // 1. Cari bengkel yang 'user_id'-nya adalah Saya (Auth::id())
        $bengkel = TambalBan::where('user_id', Auth::id())->first();

        // 2. JIKA TIDAK DITEMUKAN (Belum diassign oleh Admin)
        if (!$bengkel) {
            return view('owner.pending'); // Tampilkan halaman "Menunggu Konfirmasi"
        }

        // 3. JIKA DITEMUKAN (Sudah punya bengkel)
        $orders = Order::where('tambal_ban_id', $bengkel->id)
            ->with(['user'])
            ->latest()
            ->get();

        return view('owner.dashboard', compact('orders', 'bengkel'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->tambalBan->user_id != Auth::id()) {
            abort(403);
        }

        $action = $request->action;
        $status = 'pending';
        $alasan = null; // Default kosong

        if ($action == 'accept') {
            $status = 'proses';
        } elseif ($action == 'reject') {
            $status = 'batal';
            // Simpan alasan dari input form/request
            $alasan = $request->alasan ?? 'Maaf, bengkel tidak dapat menerima pesanan saat ini.';
        } elseif ($action == 'finish') {
            $status = 'selesai';
        }

        $order->update([
            'status' => $status,
            'alasan_batal' => $alasan // Simpan alasan
        ]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);

        // Security: Pastikan order ini milik bengkel si Owner
        if ($order->tambalBan->user_id != Auth::id()) {
            abort(403, 'Pesanan ini bukan untuk bengkel Anda.');
        }

        return view('owner.show', compact('order'));
    }
}
