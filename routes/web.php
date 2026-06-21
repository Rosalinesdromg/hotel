<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MyBookingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CEODashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\CustomerOrderController;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Tipe Kamar (resepsionis, manager, ceo)
Route::middleware(['auth', 'role:resepsionis,manager,ceo'])->group(function () {
    Route::resource('room-types', RoomTypeController::class);
});

Route::middleware(['auth', 'role:resepsionis,manager,ceo'])->group(function () {
    Route::resource('room-types', RoomTypeController::class);
    Route::resource('rooms', RoomController::class); // tambah ini
});

Route::middleware(['auth', 'role:resepsionis,manager,ceo'])->group(function () {
    Route::resource('room-types', RoomTypeController::class); 
    Route::get('/bookings/check-availability',   [BookingController::class, 'checkAvailability']);
    Route::resource('rooms', RoomController::class);
    Route::resource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/check-in',  [BookingController::class, 'checkIn']);
    Route::post('/bookings/{booking}/check-out', [BookingController::class, 'checkOut']);
   
});

Route::middleware(['auth', 'role:kasir,manager,ceo'])->group(function () {
    Route::resource('menus', MenuController::class);
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/void', [OrderController::class, 'void']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::middleware(['auth', 'role:manager,ceo'])->group(function () {
    Route::get('/reports',    [ReportController::class,   'index']);
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/my-bookings',                    [MyBookingController::class, 'index']);
    Route::get('/my-bookings/{booking}',          [MyBookingController::class, 'show']);
    Route::get('/my-bookings/{booking}/invoice',  [MyBookingController::class, 'invoice']);
});

Route::middleware(['auth', 'role:ceo'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::get('/', [LandingController::class, 'index']);
Route::get('/landing/check-availability', [LandingController::class, 'checkAvailability']);

// Customer — tulis review
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/reviews/{booking}/create',  [ReviewController::class, 'create']);
    Route::post('/reviews/{booking}',        [ReviewController::class, 'store']);
});

// Manager/CEO — kelola review
Route::middleware(['auth', 'role:manager,ceo'])->group(function () {
    Route::get('/reviews',                       [ReviewController::class, 'index']);
    Route::post('/reviews/{review}/approve',     [ReviewController::class, 'approve']);
    Route::post('/reviews/{review}/reject',      [ReviewController::class, 'reject']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function() {
        $user = auth()->user();
        if ($user->hasRole('ceo')) {
            return app(CEODashboardController::class)->index();
        }
        return app(DashboardController::class)->index();
    });
});

Route::middleware(['auth', 'role:resepsionis,manager,ceo'])->group(function () {
    Route::get('/housekeeping',                          [HousekeepingController::class, 'index']);
    Route::post('/housekeeping/{room}/clean',            [HousekeepingController::class, 'markClean']);
    Route::post('/housekeeping/{room}/dirty',            [HousekeepingController::class, 'markDirty']);
    Route::post('/housekeeping/{room}/maintenance',      [HousekeepingController::class, 'markMaintenance']);
});

// Customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/refunds/{booking}/create', [RefundController::class, 'create']);
    Route::post('/refunds/{booking}',       [RefundController::class, 'store']);
});

// Manager & CEO
Route::middleware(['auth', 'role:manager,ceo'])->group(function () {
    Route::get('/refunds',                      [RefundController::class, 'index']);
    Route::post('/refunds/{booking}/approve',   [RefundController::class, 'approve']);
    Route::post('/refunds/{booking}/reject',    [RefundController::class, 'reject']);
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/bookings/create',      [CustomerBookingController::class, 'create']);
    Route::post('/customer/bookings',            [CustomerBookingController::class, 'store']);
    Route::get('/customer/check-availability',   [CustomerBookingController::class, 'checkAvailability']);

    // My bookings yang sudah ada
    Route::get('/my-bookings',                   [MyBookingController::class, 'index']);
    Route::get('/my-bookings/{booking}',         [MyBookingController::class, 'show']);
    Route::get('/my-bookings/{booking}/invoice', [MyBookingController::class, 'invoice']);

    Route::get('/customer/orders',         [CustomerOrderController::class, 'index']);
    Route::get('/customer/orders/create',  [CustomerOrderController::class, 'create']);
    Route::post('/customer/orders',        [CustomerOrderController::class, 'store']);
});

Route::middleware(['auth', 'role:manager,ceo'])->group(function () {
    Route::get('/reports',                [ReportController::class, 'index']);
    Route::get('/reports/export/excel',   [ReportController::class, 'exportExcel']);
    Route::get('/reports/export/pdf',     [ReportController::class, 'exportPdf']);
    Route::get('/audit-logs',             [AuditLogController::class, 'index']);
});

Route::middleware(['auth', 'role:kasir,manager,ceo'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index']);
    // route orders yang sudah ada tetap dipakai untuk proses order
});

Route::middleware(['auth', 'role:kasir,manager,ceo'])->group(function () {
    Route::resource('menus', MenuController::class);
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/void',    [OrderController::class, 'void']);
    Route::get('/orders/{order}/struk',    [OrderController::class, 'struk']); // tambah ini
    Route::get('/kasir',                   [KasirController::class, 'index']);
});

Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm']);

Route::post('/rooms/bulk', [RoomController::class, 'bulkStore']);
Route::post('/menus/bulk', [MenuController::class, 'bulkStore']);

Route::post('/room-types/{roomType}/update-price', [RoomTypeController::class, 'updatePrice']);
Route::post('/menus/{menu}/update-field',           [MenuController::class, 'updateField']);
Route::post('/menus/bulk',                          [MenuController::class, 'bulkStore']);

// Notifikasi API
Route::middleware(['auth'])->group(function () {
    Route::get('/api/notifications',           function () {
        $role  = auth()->user()->getRoleNames()->first();
        $notifs = \App\Models\Notification::where('role', $role)
            ->where('is_read', false)
            ->latest()->take(10)->get();
        return response()->json($notifs);
    });

    Route::post('/api/notifications/{id}/read', function ($id) {
        \App\Models\Notification::where('id', $id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    Route::post('/api/notifications/read-all', function () {
        $role = auth()->user()->getRoleNames()->first();
        \App\Models\Notification::where('role', $role)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });
});
require __DIR__.'/auth.php';
