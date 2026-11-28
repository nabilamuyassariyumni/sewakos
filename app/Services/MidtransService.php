<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        // TAMBAHKAN BARIS INI UNTUK MEMATIKAN SSL SEMENTARA
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];
    }

    public function getSnapToken(array $params): string
    {
        // KITA BYPASS LIBRARY MIDTRANS UNTUK MELIHAT ERROR ASLINYA
        $serverKey = config('midtrans.server_key');
        $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':')
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Matikan SSL sementara
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        // Jika Midtrans membalas dengan error (HTTP Code bukan 200/201)
        if ($httpCode != 201 && $httpCode != 200) {
            // Tampilkan pesan error asli dari Midtrans
            dd('HTTP Code: ' . $httpCode, 'Response Midtrans:', $result);
        }

        return $result['token'];
    }

    public function callback(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        try {
            // Ambil notifikasi dan verifikasi keasliannya
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification'], 403);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Cari booking berdasarkan order_id
        $booking = Booking::where('midtrans_order_id', $orderId)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Update status berdasarkan notifikasi Midtrans
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // Status masih menunggu (challenge)
                } else {
                    $booking->update(['status_pembayaran' => 'berhasil']);
                }
            }
        } elseif ($transaction == 'settlement') {
            $booking->update([
                'status_pembayaran' => 'berhasil',
                'payment_type' => $type,
                'midtrans_transaction_id' => $notif->transaction_id,
            ]);
        } elseif ($transaction == 'pending') {
            $booking->update(['status_pembayaran' => 'menunggu_pembayaran']);
        } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
            $booking->update(['status_pembayaran' => 'gagal']);
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}
