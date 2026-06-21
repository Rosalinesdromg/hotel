<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Menu;
use App\Models\Room;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $todayOrders   = Order::whereDate('created_at', today())->where('status', 'paid')->count();
        $todayRevenue  = Order::whereDate('created_at', today())->where('status', 'paid')->sum('total_price');
        $walkinCount   = Order::whereDate('created_at', today())->where('type', 'walkin')->where('status', 'paid')->count();
        $roomSvcCount  = Order::whereDate('created_at', today())->where('type', 'room_service')->where('status', 'paid')->count();

        // Order terbaru hari ini
        $recentOrders  = Order::whereDate('created_at', today())
            ->with(['items.menu', 'booking.room'])
            ->latest()->take(10)->get();

        // Tamu yang sedang check-in (untuk room service)
        $activeGuests  = Booking::where('status', 'checked_in')
            ->with(['user', 'room.roomType', 'orders' => function($q) {
                $q->where('type', 'room_service')
                  ->where('status', 'paid')
                  ->whereDate('created_at', today());
            }])
            ->get();

        // Menu tersedia
        $menus = Menu::where('is_available', true)->get()->groupBy('category');

        // Tambah ini di dalam method index, sebelum return view
        $pendingOrders = Order::where('status', 'pending')
            ->with(['items.menu', 'user'])
            ->latest()->get();

       return view('kasir.index', compact(
        'todayOrders', 'todayRevenue', 'walkinCount', 'roomSvcCount',
        'recentOrders', 'activeGuests', 'menus', 'pendingOrders'
    ));
    }
}