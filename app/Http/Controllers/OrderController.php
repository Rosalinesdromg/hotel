<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Booking;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['booking.user', 'items.menu', 'user'])
            ->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menus    = Menu::where('is_available', true)->get()->groupBy('category');
        $bookings = Booking::where('status', 'checked_in')
            ->with('user', 'room')->get();
        return view('orders.create', compact('menus', 'bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'type'           => 'required|in:walkin,room_service',
            'payment_method' => 'required_if:type,walkin',
            'booking_id'     => 'required_if:type,room_service|nullable|exists:bookings,id',
        ]);

        // Hitung total
        $total = 0;
        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $total += $menu->price * $item['quantity'];
        }

        $order = Order::create([
            'order_code'     => Order::generateCode(),
            'booking_id'     => $request->booking_id,
            'user_id'        => auth()->id(),
            'type'           => $request->type,
            'total_price'    => $total,
            'payment_method' => $request->type === 'walkin' ? $request->payment_method : 'charge_to_room',
            'status'         => 'paid',
        ]);

        // Simpan item & kurangi stok
        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            OrderItem::create([
                'order_id'  => $order->id,
                'menu_id'   => $menu->id,
                'quantity'  => $item['quantity'],
                'price'     => $menu->price,
            ]);
            $menu->decrement('stock', $item['quantity']);
            if ($menu->stock <= 0) {
                $menu->update(['is_available' => false]);
            }
        }

        // Setelah loop foreach items selesai
        $order->load(['items.menu', 'booking.room']);
        Notification::orderCreated($order);

        // Redirect ke struk
    return redirect('/orders/' . $order->id . '/struk')
        ->with('success', 'Order berhasil diproses!');
}

    public function show(Order $order)
    {
        $order->load(['items.menu', 'booking.user', 'user']);
        return view('orders.show', compact('order'));
    }

    // Void order — butuh role manager
    public function void(Request $request, Order $order)
    {
        if (!auth()->user()->hasRole('manager') && !auth()->user()->hasRole('ceo')) {
            abort(403, 'Hanya Manager yang bisa void order.');
        }

        $order->update([
            'status'    => 'void',
            'voided_by' => auth()->id(),
        ]);

        // Kembalikan stok
        foreach ($order->items as $item) {
            $item->menu->increment('stock', $item->quantity);
            $item->menu->update(['is_available' => true]);
        }

        // Catat di audit log
        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'void order',
            'model_type'  => 'Order',
            'model_id'    => $order->id,
            'description' => 'Order ' . $order->order_code . ' di-void oleh ' . auth()->user()->name,
        ]);

        return back()->with('success', 'Order berhasil di-void.');
    }

    // TAMBAH INI TEPAT DI BAWAH void
        public function confirm(Request $request, Order $order)
        {
            $request->validate([
                'payment_method' => 'required|in:cash,debit',
            ]);

            $order->update([
                'status'         => 'paid',
                'payment_method' => $request->payment_method,
            ]);

            return redirect('/orders/' . $order->id . '/struk')
                ->with('success', 'Pembayaran dikonfirmasi!');
        }

    public function struk(Order $order)
    {
        $order->load(['items.menu', 'booking.room.roomType', 'booking.user', 'user']);
        return view('orders.struk', compact('order'));
    }
}