<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->orderBy('room_number')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number'  => 'required|string|unique:rooms,room_number',
        ]);

        Room::create($request->only('room_type_id', 'room_number'));
        return redirect('/rooms')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number'  => 'required|string|unique:rooms,room_number,' . $room->id,
            'status'       => 'required|in:available,occupied,dirty,maintenance',
        ]);

        $room->update($request->only('room_type_id', 'room_number', 'status'));
        return redirect('/rooms')->with('success', 'Kamar berhasil diupdate.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect('/rooms')->with('success', 'Kamar berhasil dihapus.');
    }

    public function bulkStore(Request $request)
{
    $request->validate([
        'rooms'                    => 'required|array|min:1',
        'rooms.*.room_number'      => 'required|string|distinct|unique:rooms,room_number',
        'rooms.*.room_type_id'     => 'required|exists:room_types,id',
    ]);

    foreach ($request->rooms as $room) {
        Room::create([
            'room_number'  => $room['room_number'],
            'room_type_id' => $room['room_type_id'],
        ]);
    }

    return redirect('/rooms')->with('success', count($request->rooms) . ' kamar berhasil ditambahkan.');
}
}