<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code', 'user_id', 'room_id', 'package',
        'check_in', 'check_out', 'guest_count', 'extra_bed',
        'total_price', 'dp_amount', 'payment_status', 'payment_method',
        'bank_option', 'status', 'refund_status', 'refund_reason', 'refund_data'
    ]; // tambah ini

    protected $casts = [
        'check_in'  => 'date',
        'check_out' => 'date',
        'extra_bed' => 'boolean',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function room()   { return $this->belongsTo(Room::class); }
    public function orders() { return $this->hasMany(Order::class); }

    // Hitung total tagihan termasuk room service
    public function grandTotal()
    {
        $roomServiceTotal = $this->orders()
            ->where('status', 'paid')
            ->where('type', 'room_service')
            ->sum('total_price');
        return $this->total_price + $roomServiceTotal;
    }

    // Generate kode booking otomatis
    public static function generateCode()
    {
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', today())->count() + 1;
        return 'LH-' . $date . '-' . str_pad($last, 3, '0', STR_PAD_LEFT);
    }
}