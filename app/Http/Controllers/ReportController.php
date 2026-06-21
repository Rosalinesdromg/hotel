<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Room;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        // Revenue dari kamar
        $bookingRevenue = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('payment_status', ['dp', 'paid'])
            ->sum('dp_amount');

        // Revenue dari restoran
        $orderRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'paid')
            ->where('type', 'walkin')
            ->sum('total_price');

        $totalRevenue = $bookingRevenue + $orderRevenue;

        // Total booking
        $totalBookings   = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalCheckouts  = Booking::whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'checked_out')->count();
        $cancelledCount  = Booking::whereBetween('created_at', [$startDate, $endDate])
                            ->where('status', 'cancelled')->count();

        // Occupancy rate
        $totalRooms      = Room::count();
        $occupiedDays    = Booking::whereBetween('created_at', [$startDate, $endDate])
                            ->whereIn('status', ['checked_in', 'checked_out'])->count();
        $occupancyRate   = $totalRooms > 0
                            ? round(($occupiedDays / max($totalRooms, 1)) * 100, 1)
                            : 0;

        // Top menu
        $topMenus = Menu::withSum('orderItems', 'quantity')
                    ->orderByDesc('order_items_sum_quantity')
                    ->take(10)->get();

        // Booking per tipe kamar
        $bookingsByType = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with('room.roomType')
            ->get()
            ->groupBy(fn($b) => $b->room->roomType->name ?? 'Unknown')
            ->map->count();

        // Data tabel booking
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'room.roomType'])
            ->latest()->get();

        // Data tabel order
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.menu'])
            ->where('status', 'paid')
            ->latest()->get();

        return view('reports.index', compact(
            'startDate', 'endDate',
            'bookingRevenue', 'orderRevenue', 'totalRevenue',
            'totalBookings', 'totalCheckouts', 'cancelledCount',
            'occupancyRate', 'topMenus', 'bookingsByType',
            'bookings', 'orders'
        ));
    }

    public function exportExcel(Request $request)
{
    $startDate = $request->start_date
        ? Carbon::parse($request->start_date)->startOfDay()
        : now()->startOfMonth();
    $endDate = $request->end_date
        ? Carbon::parse($request->end_date)->endOfDay()
        : now()->endOfDay();

    $filename = 'Laporan-LunarHotel-' . $startDate->format('d-m-Y') . '-sd-' . $endDate->format('d-m-Y') . '.xlsx';

    return Excel::download(new LaporanExport($startDate, $endDate), $filename);
}

public function exportPdf(Request $request)
{
    $startDate = $request->start_date
        ? Carbon::parse($request->start_date)->startOfDay()
        : now()->startOfMonth();
    $endDate = $request->end_date
        ? Carbon::parse($request->end_date)->endOfDay()
        : now()->endOfDay();

    $bookingRevenue = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])
        ->whereIn('payment_status', ['dp', 'paid'])->sum('dp_amount');
    $orderRevenue = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])
        ->where('status', 'paid')->sum('total_price');
    $totalRevenue = $bookingRevenue + $orderRevenue;

    $bookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])
        ->with(['user', 'room.roomType'])->latest()->get();
    $orders = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])
        ->where('status', 'paid')->with(['items.menu'])->latest()->get();

    $pdf = Pdf::loadView('reports.pdf', compact(
        'startDate', 'endDate',
        'bookingRevenue', 'orderRevenue', 'totalRevenue',
        'bookings', 'orders'
    ))->setPaper('a4', 'landscape');

    return $pdf->download('Laporan-LunarHotel-' . $startDate->format('d-m-Y') . '.pdf');
}
}