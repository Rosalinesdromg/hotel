<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name', 'description', 'capacity',
        'base_price', 'weekend_price', 'image'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function availableRooms()
    {
        return $this->hasMany(Room::class)->where('status', 'available');
    }
}