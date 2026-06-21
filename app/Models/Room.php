<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_type_id', 'room_number', 'status'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailableOn($checkIn, $checkOut)
    {
        return !$this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function($q2) use ($checkIn, $checkOut) {
                      $q2->where('check_in', '<=', $checkIn)
                         ->where('check_out', '>=', $checkOut);
                  });
            })->exists();
    }
}