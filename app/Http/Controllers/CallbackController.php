<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class CallbackController extends Controller
{
    public function callback()
    {
        // 1. Konfigurasi Ulang Midtrans (Wajib)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            // 2. Tangkap Notifikasi dari Midtrans
            $notification = new Notification();

            // 3. Ambil Data Penting
            $status = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;
            $order_id = $notification->order_id; 

            // 4. Cari Order di Database Kita
            // Hati-hati: Midtrans kirim order_id string, pastikan match dengan database
            $order = Order::where('id', $order_id)->orWhere('kode_order', $order_id)->firstOrFail();

            // 5. Logika Update Status Pembayaran
            if ($status == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                    } else {
                        $order->update(['payment_status' => 'paid']);
                    }
                }
            } else if ($status == 'settlement') {
                // INI YANG PALING PENTING (Sukses Bayar: VA, Gopay, Indomaret, dll)
                $order->update(['payment_status' => 'paid']);
                
                // Opsional: Langsung ubah status pesanan jadi 'proses' jika sudah bayar
                // $order->update(['status' => 'proses']); 
                
            } else if ($status == 'pending') {
                $order->update(['payment_status' => 'pending']);
            } else if ($status == 'deny') {
                $order->update(['payment_status' => 'failed']);
            } else if ($status == 'expire') {
                $order->update(['payment_status' => 'expired']);
            } else if ($status == 'cancel') {
                $order->update(['payment_status' => 'failed']);
            }

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing notification', 'error' => $e->getMessage()], 500);
        }
    }
}