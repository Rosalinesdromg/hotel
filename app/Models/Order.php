<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_code', 'booking_id', 'user_id', 'type',
        'total_price', 'payment_method', 'status', 'voided_by'
    ];

    public function booking()    { return $this->belongsTo(Booking::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function voidedBy()   { return $this->belongsTo(User::class, 'voided_by'); }
    public function items()      { return $this->hasMany(OrderItem::class); }

    public static function generateCode()
    {
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', today())->count() + 1;
        return 'ORD-' . $date . '-' . str_pad($last, 3, '0', STR_PAD_LEFT);
    }
}