<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Notification;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.menu'])
            ->latest()->get();
        return view('customer.orders', compact('orders'));
    }

    public function create()
    {
        $menus = Menu::where('is_available', true)->get()->groupBy('category');

        // Cek apakah customer sedang check-in
        $activeBooking = Booking::where('user_id', auth()->id())
            ->where('status', 'checked_in')
            ->with('room')
            ->first();

        return view('customer.order-create', compact('menus', 'activeBooking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $menu   = Menu::findOrFail($item['menu_id']);
            $total += $menu->price * $item['quantity'];
        }

        // Kalau sedang check-in → room service, kalau tidak → harus datang langsung
        $activeBooking = Booking::where('user_id', auth()->id())
            ->where('status', 'checked_in')->first();

        if ($activeBooking) {
            $order = Order::create([
                'order_code'     => Order::generateCode(),
                'booking_id'     => $activeBooking->id,
                'user_id'        => auth()->id(),
                'type'           => 'room_service',
                'total_price'    => $total,
                'payment_method' => 'charge_to_room',
                'status'         => 'paid',
            ]);
        } else {
            // Walk-in — harus bayar langsung nanti di kasir
            $order = Order::create([
                'order_code'     => Order::generateCode(),
                'booking_id'     => null,
                'user_id'        => auth()->id(),
                'type'           => 'walkin',
                'total_price'    => $total,
                'payment_method' => null,
                'status'         => 'pending', // kasir yang konfirmasi
            ]);
        }

        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id'  => $menu->id,
                'quantity' => $item['quantity'],
                'price'    => $menu->price,
            ]);
            $menu->decrement('stock', $item['quantity']);
            if ($menu->stock <= 0) {
                $menu->update(['is_available' => false]);
            }
        }

        // Setelah loop foreach items selesai
        $order->load(['items.menu', 'booking.room']);
        Notification::orderCreated($order);

        return redirect('/customer/orders')
            ->with('success', 'Pesanan berhasil dikirim! Kode: ' . $order->order_code);
    }
}