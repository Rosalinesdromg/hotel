<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stat cards
        $availableRooms  = Room::where('status', 'available')->count();
        $totalRooms      = Room::count();
        $todayCheckIn    = Booking::whereDate('check_in', today())
                            ->where('status', 'confirmed')->count();
        $currentGuests   = Booking::where('status', 'checked_in')->count();
        $monthRevenue    = Booking::whereMonth('created_at', now()->month)
                            ->whereIn('payment_status', ['dp', 'paid'])
                            ->sum('dp_amount');
        $todayOrders     = Order::whereDate('created_at', today())
                            ->where('status', 'paid')->count();
        $todayOrderRev   = Order::whereDate('created_at', today())
                            ->where('status', 'paid')->sum('total_price');

        // Booking terbaru
        $recentBookings  = Booking::with(['user', 'room.roomType'])
                            ->latest()->take(5)->get();

        // Order terbaru
        $recentOrders    = Order::with(['items.menu'])
                            ->latest()->take(5)->get();

        // Status kamar
        $roomStats = [
            'available'   => Room::where('status', 'available')->count(),
            'occupied'    => Room::where('status', 'occupied')->count(),
            'dirty'       => Room::where('status', 'dirty')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];

        // Top menu
        $topMenus = Menu::withSum('orderItems', 'quantity')
                    ->orderByDesc('order_items_sum_quantity')
                    ->take(5)->get();

        return view('dashboard', compact(
            'availableRooms', 'totalRooms', 'todayCheckIn',
            'currentGuests', 'monthRevenue', 'todayOrders',
            'todayOrderRev', 'recentBookings', 'recentOrders',
            'roomStats', 'topMenus'
        ));
    }
}