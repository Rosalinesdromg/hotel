<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use App\Models\Notification;

class CustomerBookingController extends Controller
{
    public function create()
    {
        $roomTypes = RoomType::with('rooms')->get();
        $prefill = [
            'room_type_id' => request('room_type_id'),
            'check_in'     => request('check_in'),
            'check_out'    => request('check_out'),
        ];
        return view('customer.booking', compact('roomTypes', 'prefill'));
    }

    public function store(Request $request)
{
    $request->validate([
        'room_id'         => 'required|exists:rooms,id',
        'check_in'        => 'required|date|after_or_equal:today',
        'check_out'       => 'required|date|after:check_in',
        'guest_count'     => 'required|integer|min:1',
        'package'         => 'required|in:room_only,with_breakfast,full_package',
        'payment_type'    => 'required|in:dp,full',
        'payment_method'  => 'required|in:cash,card',
        'total_price'     => 'required|numeric',
    ]);

    $room = Room::findOrFail($request->room_id);
    if (!$room->isAvailableOn($request->check_in, $request->check_out)) {
        return back()->with('error', 'Kamar sudah dipesan di tanggal tersebut.');
    }

    $dpAmount = $request->payment_type === 'dp'
        ? $request->total_price * 0.3
        : $request->total_price;

    $booking = Booking::create([
        'booking_code'    => Booking::generateCode(),
        'user_id'         => auth()->id(),
        'room_id'         => $request->room_id,
        'package'         => $request->package,
        'check_in'        => $request->check_in,
        'check_out'       => $request->check_out,
        'guest_count'     => $request->guest_count,
        'extra_bed'       => $request->has('extra_bed'),
        'total_price'     => $request->total_price,
        'dp_amount'       => $dpAmount,
        'payment_status'  => $request->payment_type === 'dp' ? 'dp' : 'paid',
        'payment_method' => $request->payment_method,
        'bank_option'    => $request->bank_option,
        'status'          => 'confirmed',
    ]);

    $booking->load(['user', 'room.roomType']);
    // Setelah $booking->load(['user', 'room.roomType']);
    Notification::bookingCreated($booking);
    Mail::to($booking->user->email)->send(new BookingConfirmation($booking));

    return redirect('/my-bookings/' . $booking->id . '?booked=1')
        ->with('success', 'Booking berhasil! Kode: ' . $booking->booking_code);
}

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required',
            'check_in'     => 'required|date',
            'check_out'    => 'required|date|after:check_in',
             'bank_option' => 'required_if:payment_method,card|nullable|string',
        ]);

        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $rooms = Room::where('room_type_id', $request->room_type_id)
            ->where('status', 'available')
            ->get()
            ->filter(fn($room) => $room->isAvailableOn($checkIn, $checkOut));

        $roomType   = RoomType::find($request->room_type_id);
        $nights     = $checkIn->diffInDays($checkOut);
        $totalPrice = 0;

        for ($i = 0; $i < $nights; $i++) {
            $day = $checkIn->copy()->addDays($i)->dayOfWeek;
            $isWeekend = in_array($day, [6, 0]);
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
}