<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'category' => 'required|string',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'image'    => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_available'] = true;
        Menu::create($data);
        return redirect('/menus')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'category' => 'required|string',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'image'    => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_available'] = $request->stock > 0;
        $menu->update($data);
        return redirect('/menus')->with('success', 'Menu berhasil diupdate.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect('/menus')->with('success', 'Menu berhasil dihapus.');
    }

    public function bulkStore(Request $request)
{
    $request->validate([
        'menus'              => 'required|array|min:1',
        'menus.*.name'       => 'required|string|max:100',
        'menus.*.category'   => 'required|string',
        'menus.*.price'      => 'required|numeric|min:0',
        'menus.*.stock'      => 'required|integer|min:0',
    ]);

    foreach ($request->menus as $menu) {
        Menu::create([
            'name'         => $menu['name'],
            'category'     => $menu['category'],
            'price'        => $menu['price'],
            'stock'        => $menu['stock'],
            'is_available' => true,
        ]);
    }

    return redirect('/menus')->with('success', count($request->menus) . ' menu berhasil ditambahkan.');
}

public function updateField(Request $request, Menu $menu)
{
    $request->validate([
        'field' => 'required|in:price,stock',
        'value' => 'required|numeric|min:0',
    ]);

    $data = [$request->field => $request->value];

    // Update is_available otomatis berdasarkan stok
    if ($request->field === 'stock') {
        $data['is_available'] = $request->value > 0;
    }

    $menu->update($data);

    \App\Models\AuditLog::create([
        'user_id'     => auth()->id(),
        'action'      => 'update menu ' . $request->field,
        'model_type'  => 'Menu',
        'model_id'    => $menu->id,
        'description' => auth()->user()->name . ' mengubah ' . $request->field . ' menu ' . $menu->name . ' menjadi ' . $request->value,
    ]);

    return response()->json(['success' => true]);
}
}