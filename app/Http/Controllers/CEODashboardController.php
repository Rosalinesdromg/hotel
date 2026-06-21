<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Room;
use App\Models\Menu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CEODashboardController extends Controller
{
    public function index()
    {
        // === STAT CARDS ===
        $totalRooms      = Room::count();
        $availableRooms  = Room::where('status', 'available')->count();
        $currentGuests   = Booking::where('status', 'checked_in')->count();
        $totalCustomers  = User::role('customer')->count();

        $monthRevenue = Booking::whereMonth('created_at', now()->month)
                        ->whereIn('payment_status', ['dp', 'paid'])
                        ->sum('dp_amount')
                        + Order::whereMonth('created_at', now()->month)
                        ->where('status', 'paid')->sum('total_price');

        $lastMonthRevenue = Booking::whereMonth('created_at', now()->subMonth()->month)
                        ->whereIn('payment_status', ['dp', 'paid'])
                        ->sum('dp_amount')
                        + Order::whereMonth('created_at', now()->subMonth()->month)
                        ->where('status', 'paid')->sum('total_price');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // === CHART 1: Revenue 6 bulan terakhir ===
        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $roomRev = Booking::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereIn('payment_status', ['dp', 'paid'])
                ->sum('dp_amount');
            $orderRev = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'paid')
                ->sum('total_price');
            $revenueChart[] = [
                'label'  => $month->format('M Y'),
                'room'   => (float) $roomRev,
                'order'  => (float) $orderRev,
                'total'  => (float) ($roomRev + $orderRev),
            ];
        }

        // === CHART 2: Occupancy rate per bulan ===
        $occupancyChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $checkedIn = Booking::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereIn('status', ['checked_in', 'checked_out'])
                ->count();
            $occupancyChart[] = [
                'label' => $month->format('M Y'),
                'rate'  => $totalRooms > 0 ? round(($checkedIn / $totalRooms) * 100, 1) : 0,
            ];
        }

        // === CHART 3: Top 5 menu terlaris ===
        $topMenus = Menu::withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take(5)->get();

        // === CHART 4: Booking per tipe kamar ===
        $bookingsByType = Booking::with('room.roomType')
            ->whereMonth('created_at', now()->month)
            ->get()
            ->groupBy(fn($b) => $b->room->roomType->name ?? 'Unknown')
            ->map->count();

        // === Tabel: Booking terbaru ===
        $recentBookings = Booking::with(['user', 'room.roomType'])
            ->latest()->take(8)->get();

        // === Room status ===
        $roomStats = [
            'available'   => Room::where('status', 'available')->count(),
            'occupied'    => Room::where('status', 'occupied')->count(),
            'dirty'       => Room::where('status', 'dirty')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];

        return view('ceo.dashboard', compact(
            'totalRooms', 'availableRooms', 'currentGuests',
            'totalCustomers', 'monthRevenue', 'revenueGrowth',
            'revenueChart', 'occupancyChart', 'topMenus',
            'bookingsByType', 'recentBookings', 'roomStats'
        ));
    }
}