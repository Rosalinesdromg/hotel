<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->get();
        return view('room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'capacity'      => 'required|integer|min:1',
            'base_price'    => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room-types', 'public');
        }

        RoomType::create($data);
        return redirect('/room-types')->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

    public function edit(RoomType $roomType)
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string',
            'capacity'      => 'required|integer|min:1',
            'base_price'    => 'required|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room-types', 'public');
        }

        $roomType->update($data);
        return redirect('/room-types')->with('success', 'Tipe kamar berhasil diupdate.');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return redirect('/room-types')->with('success', 'Tipe kamar berhasil dihapus.');
    }

    public function updatePrice(Request $request, RoomType $roomType)
{
    $request->validate([
        'field' => 'required|in:base_price,weekend_price',
        'value' => 'required|numeric|min:0',
    ]);

    $roomType->update([$request->field => $request->value]);

    // Catat di audit log
    \App\Models\AuditLog::create([
        'user_id'     => auth()->id(),
        'action'      => 'update price',
        'model_type'  => 'RoomType',
        'model_id'    => $roomType->id,
        'description' => auth()->user()->name . ' mengubah ' . $request->field . ' kamar ' . $roomType->name . ' menjadi Rp ' . number_format($request->value, 0, ',', '.'),
    ]);

    return response()->json(['success' => true]);
}
}