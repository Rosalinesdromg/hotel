<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class HousekeepingController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')
            ->orderByRaw("FIELD(status, 'dirty', 'maintenance', 'occupied', 'available')")
            ->get();

        $stats = [
            'dirty'       => $rooms->where('status', 'dirty')->count(),
            'maintenance' => $rooms->where('status', 'maintenance')->count(),
            'available'   => $rooms->where('status', 'available')->count(),
            'occupied'    => $rooms->where('status', 'occupied')->count(),
        ];

        return view('housekeeping.index', compact('rooms', 'stats'));
    }

    public function markClean(Room $room)
    {
        abort_if(!in_array($room->status, ['dirty', 'maintenance']), 403, 'Kamar ini tidak perlu dibersihkan.');

        $oldStatus = $room->status;
        $room->update(['status' => 'available']);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'housekeeping clean',
            'model_type'  => 'Room',
            'model_id'    => $room->id,
            'description' => auth()->user()->name . ' menandai kamar ' . $room->room_number . ' sebagai Clean (dari ' . $oldStatus . ')',
        ]);

        return back()->with('success', 'Kamar ' . $room->room_number . ' sudah ditandai bersih dan siap dipakai.');
    }

    public function markDirty(Room $room)
    {
        $room->update(['status' => 'dirty']);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'housekeeping dirty',
            'model_type'  => 'Room',
            'model_id'    => $room->id,
            'description' => auth()->user()->name . ' menandai kamar ' . $room->room_number . ' perlu dibersihkan.',
        ]);

        return back()->with('success', 'Kamar ' . $room->room_number . ' ditandai perlu dibersihkan.');
    }

    public function markMaintenance(Room $room)
    {
        $room->update(['status' => 'maintenance']);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'housekeeping maintenance',
            'model_type'  => 'Room',
            'model_id'    => $room->id,
            'description' => auth()->user()->name . ' menandai kamar ' . $room->room_number . ' dalam maintenance.',
        ]);

        return back()->with('success', 'Kamar ' . $room->room_number . ' ditandai maintenance.');
    }
}