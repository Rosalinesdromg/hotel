<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunar Hotel — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
        --cream: #f8f9fc;
        --cream-dark: #e8ecf4;
        --brown: #6b4c2a;
        --brown-light: #8b6340;
        --gold: #c9a96e;
        --gold-light: #e8d5a3;
        --dark: #0d1b2a;
        --navy: #1a2f4e;
        --sapphire: #2563a8;
        --sapphire-light: #3b7dd8;
        --sapphire-pale: #dbeafe;
        --sidebar: #0d1b2a;
        --text: #1e293b;
        --text-light: #64748b;
    }

        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Source Sans 3', sans-serif; background: var(--cream); color: var(--text); }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--sidebar); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 2px; }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top:0; left:0;
            width: 260px; height: 100vh;
            background: var(--sidebar);
            display: flex; flex-direction: column;
            z-index: 100; overflow-y: auto;
            border-right: 1px solid rgba(201,169,110,0.1);
            transition: transform 0.3s ease;
        }
        .sidebar {
        position: fixed; top:0; left:0;
        width: 260px; height: 100vh;
        background: linear-gradient(180deg, #0d1b2a 0%, #1a2f4e 100%);
        display: flex; flex-direction: column;
        z-index: 100; overflow-y: auto;
        border-right: 1px solid rgba(37,99,168,0.2);
        transition: transform 0.3s ease;
    }
    .sidebar-brand {
        padding: 28px 24px 20px;
        border-bottom: 1px solid rgba(37,99,168,0.15);
    }
    .sidebar-brand .logo {
        font-family: 'Libre Baskerville', serif;
        color: var(--gold); font-size: 20px;
        letter-spacing: 3px; font-weight: 400;
    }
    .sidebar-brand .tagline {
        color: rgba(255,255,255,0.25);
        font-size: 9px; letter-spacing: 4px;
        text-transform: uppercase; margin-top: 3px;
        font-weight: 300;
    }
    .sidebar-role {
        margin: 14px 20px;
        background: rgba(37,99,168,0.15);
        border: 1px solid rgba(37,99,168,0.3);
        border-radius: 2px; padding: 6px 12px;
        color: var(--sapphire-light); font-size: 10px;
        text-align: center; letter-spacing: 3px;
        text-transform: uppercase; font-weight: 400;
    }
        .sidebar nav a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 24px;
        color: rgba(255,255,255,0.45);
        text-decoration: none; font-size: 12px;
        font-family: 'Source Sans 3', sans-serif;
        letter-spacing: 0.5px;
        transition: all 0.2s; position: relative;
    }
    .sidebar nav a::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 2px; background: var(--sapphire-light);
        transform: scaleY(0); transition: transform 0.2s;
    }
    .sidebar nav a:hover {
        color: rgba(255,255,255,0.9);
        background: rgba(37,99,168,0.12);
    }
    .sidebar nav a.active {
        color: #fff;
        background: rgba(37,99,168,0.2);
    }
    .sidebar nav a.active::before,
    .sidebar nav a:hover::before { transform: scaleY(1); }
    .sidebar nav a i {
        width: 16px; text-align: center;
        font-size: 13px; color: var(--sapphire-light); opacity: 0.7;
    }
        .sidebar nav a.active i { opacity: 1; color: var(--gold); }
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(37,99,168,0.15);
        }
        .sidebar-user { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
        .sidebar-avatar { width: 36px; height: 36px; border-radius: 50%; overflow: hidden; border: 1px solid rgba(201,169,110,0.4); flex-shrink: 0; }
        .sidebar-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-avatar-initial {
            width: 100%; height: 100%;
            background: rgba(37,99,168,0.3);
            display: flex; align-items: center; justify-content: center;
            color: var(--sapphire-light); font-size: 14px; font-weight: 700;
        }
        .sidebar-user-name { color: #fff; font-size: 13px; }
        .sidebar-user-email { color: rgba(255,255,255,0.3); font-size: 10px; margin-top: 1px; }
        .sidebar-footer-links { display: flex; gap: 8px; margin-bottom: 10px; }
        .sidebar-footer-link {
            color: rgba(255,255,255,0.3);
            font-size: 10px; letter-spacing: 1px;
            text-transform: uppercase; text-decoration: none;
            transition: color 0.2s;
        }
        .sidebar-footer-link:hover { color: var(--sapphire-light); }
        .btn-logout { width: 100%; padding: 8px 12px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.35); border-radius: 2px; font-size: 11px; font-family: 'Source Sans 3', sans-serif; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: all 0.2s; text-align: left; display: flex; align-items: center; gap: 8px; }
        .btn-logout:hover { background: rgba(180,50,50,0.15); border-color: rgba(180,50,50,0.3); color: rgba(255,120,120,0.8); }

        /* MAIN */
        .main { margin-left: 260px; min-height: 100vh; width: calc(100% - 260px); }
        .topbar {
            background: #fff;
            padding: 0 32px;
            height: 60px;
            border-bottom: 2px solid var(--cream-dark);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 2px 8px rgba(37,99,168,0.06);
        }
        .topbar-title {
            font-family: 'Libre Baskerville', serif;
            font-size: 18px; font-weight: 400;
            color: var(--dark); letter-spacing: 0.5px;
        }
        .topbar-date { color: var(--text-light); font-size: 12px; letter-spacing: 1px; }
        .content { padding: 28px 32px; }

        /* CARDS */
        .card { background: #fff; border-radius: 4px; border: 1px solid var(--cream-dark); padding: 24px; margin-bottom: 24px; }
        .card-title { font-family: 'Libre Baskerville', serif; font-size: 16px; color: var(--dark); margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--cream-dark); letter-spacing: 0.5px; font-weight: 400; }

        /* STAT */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; border-radius: 4px; border: 1px solid var(--cream-dark); padding: 20px 24px; position: relative; overflow: hidden; transition: all 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(107,76,42,0.08); transform: translateY(-2px); }
        .stat-card::after {
            content: ''; position: absolute;
            bottom: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(to right, var(--sapphire), var(--sapphire-light), transparent);
        }
        .stat-card .label { font-size: 9px; color: var(--text-light); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; }
        .stat-card .value { font-family: 'Libre Baskerville', serif; font-size: 28px; color: var(--dark); margin-bottom: 4px; }
        .stat-card .sub { font-size: 11px; color: var(--text-light); }
        .stat-card .icon {
            position: absolute; right: 20px; top: 20px;
            color: var(--sapphire-light); font-size: 24px; opacity: 0.25;
        }

        /* TABLE */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-family: 'Source Sans 3', sans-serif; font-size: 13px; }
        thead th { background: var(--cream); padding: 10px 14px; text-align: left; color: var(--text-light); font-weight: 400; font-size: 10px; letter-spacing: 2px; text-transform: uppercase; border-bottom: 1px solid var(--cream-dark); }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #faf7f2; color: var(--text); }
        tbody tr:hover { background: #faf7f2; }
        tbody tr:last-child td { border-bottom: none; }

        /* BADGE */
        .badge { padding: 3px 10px; border-radius: 2px; font-size: 10px; font-family: 'Source Sans 3', sans-serif; letter-spacing: 1px; text-transform: uppercase; font-weight: 700; }
        .badge-available { background: #dcfce7; color: #15803d; }
        .badge-occupied  { background: #fff7ed; color: #c2410c; }
        .badge-dirty     { background: #fefce8; color: #a16207; }
        .badge-paid      { background: #dcfce7; color: #15803d; }
        .badge-pending   { background: #fff7ed; color: #c2410c; }
        .badge-dp        { background: var(--sapphire-pale); color: var(--sapphire); }

        /* BUTTON */
        .btn { padding: 8px 18px; border-radius: 2px; font-size: 11px; font-family: 'Source Sans 3', sans-serif; cursor: pointer; border: none; text-decoration: none; display: inline-block; transition: all 0.2s; letter-spacing: 1px; text-transform: uppercase; font-weight: 700; }
        .btn-gold { background: linear-gradient(135deg, var(--gold), #e8c27a); color: #fff; }
        .btn-gold:hover { background: linear-gradient(135deg, #b8944f, var(--gold)); color: #fff; }
        .btn-outline { background: transparent; border: 1px solid var(--sapphire); color: var(--sapphire); }
        .btn-outline:hover { background: var(--sapphire); color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; color: #fff; }

        /* ALERT */
        .alert-success {
            background: #dcfce7; border: 1px solid #86efac;
            color: #15803d; padding: 12px 16px; border-radius: 2px;
            margin-bottom: 20px; font-size: 13px;
            display: flex; align-items: center; gap: 10px;
            border-left: 4px solid #15803d;
        }
        .alert-error {
            background: #fef2f2; border: 1px solid #fca5a5;
            color: #dc2626; padding: 12px 16px; border-radius: 2px;
            margin-bottom: 20px; font-size: 13px;
            display: flex; align-items: center; gap: 10px;
            border-left: 4px solid #dc2626;
        }

        /* DATATABLES */
        .dataTables_wrapper { font-family: 'Source Sans 3', sans-serif; font-size: 13px; }
        .dataTables_filter input { border: 1px solid var(--cream-dark) !important; border-radius: 2px !important; padding: 6px 12px !important; outline: none !important; font-size: 12px !important; }
        .dataTables_filter input:focus { border-color: var(--sapphire) !important; }
        .dataTables_length select { border: 1px solid var(--cream-dark) !important; border-radius: 2px !important; padding: 4px 8px !important; }
        .dataTables_info { color: var(--text-light) !important; font-size: 12px !important; }
        .paginate_button { border-radius: 2px !important; border: 1px solid var(--cream-dark) !important; padding: 4px 10px !important; margin: 0 2px !important; cursor: pointer !important; font-size: 12px !important; }
        .paginate_button.current {
            background: var(--sapphire) !important;
            color: #fff !important;
            border-color: var(--sapphire) !important;
        }
        .paginate_button:hover {
            background: var(--sapphire-pale) !important;
            border-color: var(--sapphire) !important;
            color: var(--sapphire) !important;
        }
        table.dataTable thead th { border-bottom: 1px solid var(--cream-dark) !important; }
        table.dataTable tbody tr:hover { background: #faf7f2 !important; }

        /* MOBILE */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(13,27,42,0.6);
            backdrop-filter: blur(2px);
            z-index: 99;
        }
        .sidebar-overlay.open { display: block; }
        .mobile-topbar-burger { display: none; background: transparent; border: none; cursor: pointer; flex-direction: column; gap: 5px; padding: 4px; }
        .mobile-topbar-burger span {
            display: block;
            width: 22px; height: 2px;
            background: var(--sapphire);
            border-radius: 2px;
            transition: all 0.3s;
        }
        .mobile-topbar-burger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .mobile-topbar-burger.open span:nth-child(2) { opacity: 0; }
        .mobile-topbar-burger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-260px); z-index: 200; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0 !important; width: 100% !important; }
            .topbar { padding: 0 16px; }
            .content { padding: 20px 16px; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .mobile-topbar-burger { display: flex; }
        }
        @media (max-width: 640px) {
            .stat-grid { grid-template-columns: 1fr; }
        }
        @media (min-width: 1025px) {
            .sidebar { transform: translateX(0) !important; }
            .main { margin-left: 260px !important; width: calc(100% - 260px) !important; }
            .mobile-topbar-burger { display: none !important; }
            .sidebar-overlay { display: none !important; }
        }
    </style>

    @role('resepsionis|kasir|manager|ceo')
<script>
let notifOpen = false;

function toggleNotif() {
    notifOpen = !notifOpen;
    document.getElementById('notif-dropdown').style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) fetchNotifications();
}

// Tutup kalau klik di luar
document.addEventListener('click', function(e) {
    const btn      = document.getElementById('notif-btn');
    const dropdown = document.getElementById('notif-dropdown');
    if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
        notifOpen = false;
        dropdown.style.display = 'none';
    }
});

async function fetchNotifications() {
    try {
        const res   = await fetch('/api/notifications');
        const data  = await res.json();
        const badge = document.getElementById('notif-badge');
        const list  = document.getElementById('notif-list');

        // Update badge
        if (data.length > 0) {
            badge.style.display = 'flex';
            badge.textContent   = data.length > 9 ? '9+' : data.length;
        } else {
            badge.style.display = 'none';
        }

        // Update list
        if (data.length === 0) {
            list.innerHTML = `
                <div style="padding:24px;text-align:center;color:var(--text-light);font-size:13px;font-family:Arial">
                    <i class="fas fa-bell-slash" style="font-size:24px;margin-bottom:8px;display:block;opacity:0.3"></i>
                    Tidak ada notifikasi baru
                </div>`;
            return;
        }

        const icons = { booking: 'fa-calendar-check', order: 'fa-utensils' };
        const colors = { booking: '#2e7d32', order: '#b35c00' };

        list.innerHTML = data.map(n => `
            <div onclick="readNotif(${n.id}, '${n.url}')"
                style="padding:14px 16px;border-bottom:1px solid var(--cream-dark);cursor:pointer;transition:background 0.2s;display:flex;gap:12px;align-items:flex-start"
                onmouseover="this.style.background='var(--cream)'"
                onmouseout="this.style.background='#fff'">
                <div style="width:36px;height:36px;border-radius:50%;background:${colors[n.type]}20;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas ${icons[n.type] || 'fa-bell'}" style="color:${colors[n.type]};font-size:14px"></i>
                </div>
                <div style="flex:1">
                    <div style="font-size:13px;color:var(--dark);font-family:Arial;font-weight:700;margin-bottom:2px">${n.title}</div>
                    <div style="font-size:12px;color:var(--text-light);font-family:Arial">${n.message}</div>
                    <div style="font-size:10px;color:#aaa;font-family:Arial;margin-top:4px;letter-spacing:1px">
                        ${new Date(n.created_at).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'})}
                    </div>
                </div>
                <div style="width:8px;height:8px;background:var(--gold);border-radius:50%;flex-shrink:0;margin-top:4px"></div>
            </div>
        `).join('');

    } catch(e) {
        console.error('Notif error:', e);
    }
}

async function readNotif(id, url) {
    await fetch(`/api/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    if (url) window.location.href = url;
    else fetchNotifications();
}

async function readAllNotif() {
    await fetch('/api/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    fetchNotifications();
}

// Polling setiap 15 detik
fetchNotifications();
// setInterval(fetchNotifications, 15000); // matikan dulu
</script>
@endrole

    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="logo">☽ LUNAR</div>
        <div class="tagline">Hotel & Resort</div>
    </div>

    <div class="sidebar-role">
        {{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
    </div>

    <nav>
        @role('ceo|manager')
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        @endrole

        @role('resepsionis')
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        @endrole

        @role('resepsionis|manager|ceo')
        <a href="/rooms" class="{{ request()->is('rooms*') ? 'active' : '' }}">
            <i class="fas fa-door-open"></i> Kamar
        </a>
        <a href="/room-types" class="{{ request()->is('room-types*') ? 'active' : '' }}">
        <i class="fas fa-layer-group"></i> Tipe Kamar
        </a>
        <a href="/bookings" class="{{ request()->is('bookings*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i> Reservasi
        </a>
        <a href="/housekeeping" class="{{ request()->is('housekeeping*') ? 'active' : '' }}">
            <i class="fas fa-broom"></i> Housekeeping
        </a>
        @php
    $newRoomService = 0;
    if(auth()->check() && auth()->user()->hasAnyRole(['resepsionis','manager','ceo'])) {
        $newRoomService = \App\Models\Order::where('type', 'room_service')
            ->where('status', 'paid')
            ->whereDate('created_at', today())
            ->count();
    }
    @endphp
        <a href="/kasir" class="{{ request()->is('kasir*') ? 'active' : '' }}" style="position:relative">
            <i class="fas fa-concierge-bell"></i> Room Service
            @if($newRoomService > 0)
            <span style="position:absolute;right:16px;top:50%;transform:translateY(-50%);background:var(--gold);color:#fff;font-size:9px;padding:1px 7px;border-radius:10px;letter-spacing:0">
                {{ $newRoomService }}
            </span>
            @endif
        </a>
        @endrole

        @role('kasir|manager|ceo')
        <a href="/kasir" class="{{ request()->is('kasir*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> Kasir
        </a>
        <a href="/menus" class="{{ request()->is('menus*') ? 'active' : '' }}">
        <i class="fas fa-utensils"></i> Menu Restoran
        </a>
        @endrole

        @role('customer')
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Beranda
        </a>
        <a href="/customer/bookings/create" class="{{ request()->is('customer/bookings*') ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> Pesan Kamar
        </a>
        <a href="/my-bookings" class="{{ request()->is('my-bookings*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i> Booking Saya
        </a>
        <a href="/customer/orders/create" class="{{ request()->is('customer/orders/create') ? 'active' : '' }}">
            <i class="fas fa-utensils"></i> Pesan Makanan
        </a>
        <a href="/customer/orders" class="{{ request()->is('customer/orders') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i> Pesanan Saya
        </a>
        @endrole

        @role('manager|ceo')
        <a href="/reports" class="{{ request()->is('reports*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Laporan
        </a>
        <a href="/audit-logs" class="{{ request()->is('audit-logs*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Audit Log
        </a>
        <a href="/reviews" class="{{ request()->is('reviews*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Review Tamu
        </a>
        <a href="/refunds" class="{{ request()->is('refunds*') ? 'active' : '' }}">
            <i class="fas fa-undo"></i> Refund
        </a>
        @endrole

        @role('ceo')
        <a href="/users" class="{{ request()->is('users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Kelola User
        </a>
        @endrole
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}">
                @else
                    <div class="sidebar-avatar-initial">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <div class="sidebar-footer-links">
            <a href="/profile" class="sidebar-footer-link">
                <i class="fas fa-user-cog" style="margin-right:4px"></i> Profil
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</div>

{{-- Overlay mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- MAIN --}}
<div class="main">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:12px">
            <button class="mobile-topbar-burger" id="sidebarBurger" onclick="toggleSidebar()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
    {{-- Bell Notifikasi --}}
    @role('resepsionis|kasir|manager|ceo')
    <div style="position:relative">
        <button onclick="toggleNotif()" id="notif-btn"
            style="background:transparent;border:none;cursor:pointer;position:relative;padding:8px;border-radius:4px;transition:background 0.2s"
            onmouseover="this.style.background='var(--cream)'" onmouseout="this.style.background='transparent'">
            <i class="fas fa-bell" style="font-size:16px;color:var(--text-light)"></i>
            <span id="notif-badge"
                style="display:none;position:absolute;top:4px;right:4px;background:#c0392b;color:#fff;font-size:9px;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:Arial;font-weight:700">
                0
            </span>
        </button>

        {{-- Dropdown notifikasi --}}
        <div id="notif-dropdown"
            style="display:none;position:absolute;right:0;top:calc(100% + 8px);width:320px;background:#fff;border:1px solid var(--cream-dark);border-radius:4px;box-shadow:0 8px 32px rgba(107,76,42,0.12);z-index:200">

            <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;border-bottom:1px solid var(--cream-dark)">
                <span style="font-family:'Libre Baskerville',serif;font-size:15px;color:var(--dark)">Notifikasi</span>
                <button onclick="readAllNotif()"
                    style="background:transparent;border:none;font-size:11px;color:var(--gold);cursor:pointer;font-family:Arial;letter-spacing:1px">
                    Tandai Semua Dibaca
                </button>
            </div>

            <div id="notif-list" style="max-height:360px;overflow-y:auto">
                <div style="padding:24px;text-align:center;color:var(--text-light);font-size:13px;font-family:Arial">
                    <i class="fas fa-bell-slash" style="font-size:24px;margin-bottom:8px;display:block;opacity:0.3"></i>
                    Tidak ada notifikasi baru
                </div>
            </div>
        </div>
    </div>
    @endrole

    <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
</div>
    </div>
    <div class="content">
        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
    document.getElementById('sidebarBurger').classList.toggle('open');
}
function closeSidebar() {
    document.querySelector('.sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
    document.getElementById('sidebarBurger').classList.remove('open');
}
</script>
@stack('scripts')

</body>
</html>