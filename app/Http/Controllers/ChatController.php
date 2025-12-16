<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Halaman Chat Room
    public function show($orderId)
    {
        $order = Order::with(['chats.sender', 'tambalBan', 'user'])->findOrFail($orderId);

        // Security Check: Hanya Pemesan atau Owner Bengkel yang boleh lihat chat
        $isBuyer = $order->user_id == Auth::id();
        $isOwner = $order->tambalBan->user_id == Auth::id();

        if (!$isBuyer && !$isOwner) {
            abort(403);
        }

        return view('chat.room', compact('order'));
    }

    // Kirim Pesan (AJAX)
    public function send(Request $request, $orderId)
    {
        $request->validate(['message' => 'required']);

        $chat = Chat::create([
            'order_id' => $orderId,
            'sender_id' => Auth::id(),
            'message' => $request->message
        ]);

        return response()->json(['status' => 'success', 'data' => $chat]);
    }

    // Ambil Pesan Terbaru (Untuk Auto Refresh)
    public function getMessages($orderId)
    {
        $chats = Chat::where('order_id', $orderId)->with('sender')->get();
        return response()->json($chats);
    }
}
