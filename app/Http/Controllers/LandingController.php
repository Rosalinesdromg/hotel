<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Menu;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->get();
        $menus     = Menu::where('is_available', true)->get()->groupBy('category');
        $reviews   = \App\Models\Review::with('user')
                        ->where('is_approved', true)
                        ->latest()->take(6)->get();

        return view('landing', compact('roomTypes', 'menus', 'reviews'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $roomTypes = RoomType::with(['rooms' => function($q) use ($checkIn, $checkOut) {
            $q->where('status', 'available');
        }])->get()->map(function($type) use ($checkIn, $checkOut) {
            $available = $type->rooms->filter(
                fn($room) => $room->isAvailableOn($checkIn, $checkOut)
            )->count();

            $nights    = $checkIn->diffInDays($checkOut);
            $totalPrice = 0;
            for ($i = 0; $i < $nights; $i++) {
                $day = $checkIn->copy()->addDays($i)->dayOfWeek;
                $isWeekend = in_array($day, [6, 0]);
                $totalPrice += $isWeekend && $type->weekend_price
                    ? $type->weekend_price : $type->base_price;
            }

            return [
                'id'            => $type->id,
                'name'          => $type->name,
                'description'   => $type->description,
                'capacity'      => $type->capacity,
                'price_per_night' => $type->base_price,
                'total_price'   => $totalPrice,
                'nights'        => $nights,
                'available'     => $available,
            ];
        });

        return response()->json($roomTypes);
    }
}