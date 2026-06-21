<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type', 'title', 'message', 'url', 'role', 'is_read'
    ];

    // Kirim notifikasi booking baru ke resepsionis
    public static function bookingCreated($booking)
    {
        self::create([
            'type'    => 'booking',
            'title'   => 'Booking Baru',
            'message' => $booking->user->name . ' memesan Kamar ' . $booking->room->room_number,
            'url'     => '/bookings/' . $booking->id,
            'role'    => 'resepsionis',
        ]);
    }

    // Kirim notifikasi order baru ke kasir
    public static function orderCreated($order)
    {
        $who  = $order->booking ? 'Kamar ' . $order->booking->room->room_number : 'Tamu Umum';
        $type = $order->type === 'room_service' ? 'Room Service' : 'Walk-in';

        self::create([
            'type'    => 'order',
            'title'   => 'Pesanan Baru — ' . $type,
            'message' => $who . ' memesan ' . $order->items->count() . ' item',
            'url'     => '/kasir',
            'role'    => 'kasir',
        ]);

        // Room service juga notif ke resepsionis
        if ($order->type === 'room_service') {
            self::create([
                'type'    => 'order',
                'title'   => 'Room Service Masuk',
                'message' => $who . ' memesan ' . $order->items->count() . ' item ke kamar',
                'url'     => '/kasir',
                'role'    => 'resepsionis',
            ]);
        }
    }
}