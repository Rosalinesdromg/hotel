<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room.roomType'])
            ->latest()->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
{
    $roomTypes = RoomType::with('rooms')->get();

    // Ambil parameter dari landing page kalau ada
    $prefill = [
        'room_type_id' => request('room_type_id'),
        'check_in'     => request('check_in'),
        'check_out'    => request('check_out'),
    ];

    return view('bookings.create', compact('roomTypes', 'prefill'));
}

    // Cek kamar tersedia via AJAX
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required',
            'check_in'     => 'required|date',
            'check_out'    => 'required|date|after:check_in',
        ]);

        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $rooms = Room::where('room_type_id', $request->room_type_id)
            ->where('status', 'available')
            ->get()
            ->filter(fn($room) => $room->isAvailableOn($checkIn, $checkOut));

        // Hitung harga otomatis (dynamic pricing weekend)
        $roomType  = RoomType::find($request->room_type_id);
        $nights    = $checkIn->diffInDays($checkOut);
        $totalPrice = 0;

        for ($i = 0; $i < $nights; $i++) {
            $day = $checkIn->copy()->addDays($i)->dayOfWeek;
            $isWeekend = in_array($day, [6, 0]); // Sabtu & Minggu
            $totalPrice += $isWeekend && $roomType->weekend_price
                ? $roomType->weekend_price
                : $roomType->base_price;
        }

        return response()->json([
            'rooms'       => $rooms->values(),
            'nights'      => $nights,
            'total_price' => $totalPrice,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'check_in'       => 'required|date|after_or_equal:today',
            'check_out'      => 'required|date|after:check_in',
            'guest_count'    => 'required|integer|min:1',
            'package'        => 'required|in:room_only,with_breakfast,full_package',
            'payment_type'   => 'required|in:dp,full',
            'total_price'    => 'required|numeric',
        ]);

        // Cek lagi biar aman (anti double booking)
        $room = Room::findOrFail($request->room_id);
        if (!$room->isAvailableOn($request->check_in, $request->check_out)) {
            return back()->with('error', 'Kamar sudah dipesan di tanggal tersebut.');
        }

        $dpAmount = $request->payment_type === 'dp'
            ? $request->total_price * 0.3  // DP 30%
            : $request->total_price;

        $booking = Booking::create([
            'booking_code'   => Booking::generateCode(),
            'user_id'        => auth()->id(),
            'room_id'        => $request->room_id,
            'package'        => $request->package,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'guest_count'    => $request->guest_count,
            'extra_bed'      => $request->has('extra_bed'),
            'total_price'    => $request->total_price,
            'dp_amount'      => $dpAmount,
            'payment_status' => $request->payment_type === 'dp' ? 'dp' : 'paid',
            'status'         => 'confirmed',
        ]);

        // Setelah Booking::create(...)
        $booking->load(['user', 'room.roomType']);
        Mail::to($booking->user->email)->send(new BookingConfirmation($booking));

        return redirect('/bookings')->with('success', 'Booking berhasil! Kode: ' . $booking->booking_code);
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.roomType', 'orders.items.menu']);
        return view('bookings.show', compact('booking'));
    }

    // Resepsionis: check-in
    public function checkIn(Booking $booking)
    {
        $booking->update(['status' => 'checked_in']);
        $booking->room->update(['status' => 'occupied']);
        return back()->with('success', 'Check-in berhasil.');
    }

    // Resepsionis: check-out
    public function checkOut(Booking $booking)
    {
        $booking->update(['status' => 'checked_out']);
        $booking->room->update(['status' => 'dirty']); // otomatis jadi kotor
        return back()->with('success', 'Check-out berhasil. Status kamar: Kotor.');
    }
}