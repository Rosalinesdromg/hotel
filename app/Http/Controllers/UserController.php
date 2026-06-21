<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['customer'])->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect('/users')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::whereNotIn('name', ['customer'])->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|exists:roles,name',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect('/users')->with('success', 'Data staff berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa hapus akun sendiri.');
        }
        $user->delete();
        return redirect('/users')->with('success', 'Staff berhasil dihapus.');
    }
}