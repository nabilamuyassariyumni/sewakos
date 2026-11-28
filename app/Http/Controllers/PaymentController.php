<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    // Halaman pembayaran untuk booking tertentu
    public function proses(Request $request, Booking $booking)
    {
        // 1. Keamanan
        if (Auth::id() !== $booking->pencari_id) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        // Cek apakah ini percobaan ulang (retry) setelah gagal
        $isRetry = $request->has('retry');

        // 2. Buat token jika belum ada ATAU jika ini percobaan ulang
        if (!$booking->snap_token || $isRetry) {

            // Jika retry, kosongkan token lama agar dibuatkan yang baru
            if ($isRetry) {
                $booking->update(['snap_token' => null, 'status_pembayaran' => 'belum_ada']);
            }

            $orderId = 'BOOKING-' . $booking->id . '-' . time();

            // Pastikan semua data berupa tipe yang benar
            $grossAmount = (int) $booking->total_harga;
            $itemId = (string) $booking->kos_id;
            $kosName = substr(preg_replace('/[^A-Za-z0-9 ]/', '', $booking->kos->nama_kos), 0, 50);

            if (empty($kosName)) {
                $kosName = 'Sewa Kos';
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => $booking->pencari->name ?? 'Pencari',
                    'email' => $booking->pencari->email ?? 'pencari@example.com',
                ],
                'item_details' => [
                    [
                        'id' => $itemId,
                        'price' => $grossAmount,
                        'quantity' => 1,
                        'name' => $kosName,
                    ]
                ]
            ];

            try {
                $snapToken = $this->midtransService->getSnapToken($params);

                $booking->update([
                    'snap_token' => $snapToken,
                    'midtrans_order_id' => $orderId,
                    'status_pembayaran' => 'menunggu_pembayaran',
                ]);
            } catch (\Exception $e) {
                // Kembalikan ke halaman sebelumnya dengan pesan error yang rapi
                return redirect()->back()->with('error', 'Gagal terhubung ke Midtrans. Silakan coba beberapa saat lagi.');
            }
        }

        // 3. Tampilkan halaman pembayaran
        return view('pencari.booking.pembayaran', compact('booking'));
    }

    // Callback Midtrans
    public function callback(Request $request)
    {
        // 1. Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // 2. Ambil notifikasi dan verifikasi keasliannya
        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            // Kembalikan response JSON yang bersih (tanpa debug)
            return response()->json(['message' => 'Invalid notification'], 403);
        }

        // 3. Ambil data penting
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // 4. Cari booking berdasarkan order_id Midtrans
        $booking = Booking::where('midtrans_order_id', $orderId)->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Cegah Pemrosesan Ganda
        if ($booking->status_pembayaran === 'berhasil' && ($transaction == 'settlement' || $transaction == 'capture')) {
            return response()->json(['message' => 'Already processed']);
        }

        // 5. Update status berdasarkan notifikasi Midtrans
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // Status masih menunggu (challenge)
                } else {
                    $booking->update([
                        'status_pembayaran' => 'berhasil',
                        'payment_type' => $type,
                        'midtrans_transaction_id' => $notif->transaction_id,
                    ]);
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

        // 6. Kembalikan response 200 OK ke Midtrans
        return response()->json(['message' => 'Callback received successfully']);
    }
}
