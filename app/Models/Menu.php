<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'category', 'price', 'stock', 'is_available', 'image'
    ];

    public function orderItems() { 
        return $this->hasMany(OrderItem::class); }
}