<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
    'name'       => ['required', 'string', 'max:255'],
    'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'phone'      => ['nullable', 'string', 'max:20'],
    'birthdate'  => ['required', 'date', 'before:-17 years'], // min 17 tahun
    'password'   => ['required', 'confirmed', Rules\Password::defaults()],
]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'password' => Hash::make($request->password),
    ]);

    $user->assignRole('customer'); // otomatis jadi customer

    event(new Registered($user));
    Auth::login($user);

    // Ganti baris return redirect('/my-bookings');
    // Jadi:
    return redirect('/');
}
}
