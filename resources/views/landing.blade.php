<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunar Hotel — Classic Modern Experience</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        --text: #1e293b;
        --text-light: #64748b;
    }

        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Source Sans 3', sans-serif; background: var(--cream); color: var(--text); overflow-x: hidden; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--cream); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 3px; }

        /* ===== NAVBAR ===== */
        nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        display: flex; justify-content: space-between; align-items: center;
        padding: 0 60px; height: 72px;
        background: rgba(13, 27, 42, 0.95);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(201,169,110,0.15);
        transition: all 0.3s;
    }
    nav.scrolled {
        height: 60px;
        background: rgba(13, 27, 42, 0.99);
    }
        .nav-brand { display: flex; align-items: center; gap: 10px; }
        .nav-brand .logo { color: var(--gold); font-family: 'Libre Baskerville', serif; font-size: 22px; letter-spacing: 3px; }
        .nav-brand .tagline { color: rgba(255,255,255,0.3); font-size: 10px; letter-spacing: 4px; text-transform: uppercase; font-weight: 300; }
        .nav-center { display: flex; gap: 36px; align-items: center; position: absolute; left: 50%; transform: translateX(-50%); }
        .nav-center a { color: rgba(255,255,255,0.65); text-decoration: none; font-size: 12px; font-weight: 400; letter-spacing: 2px; text-transform: uppercase; transition: color 0.2s; position: relative; }
        .nav-center a::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 1px; background: var(--gold); transition: width 0.3s; }
        .nav-center a:hover { color: var(--gold); }
        .nav-center a:hover::after { width: 100%; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .nav-divider { width: 1px; height: 20px; background: rgba(255,255,255,0.15); }
        .nav-avatar { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .nav-avatar-img { width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--gold); overflow: hidden; }
        .nav-avatar-img img { width: 100%; height: 100%; object-fit: cover; }
        .nav-avatar-initial { width: 100%; height: 100%; background: var(--gold); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 700; }
        .nav-avatar span { color: rgba(255,255,255,0.8); font-size: 13px; }
        .nav-link { color: rgba(255,255,255,0.65); text-decoration: none; font-size: 12px; letter-spacing: 1px; text-transform: uppercase; transition: color 0.2s; }
        .nav-link:hover { color: var(--gold); }
        .nav-btn-outline {
        border: 1px solid var(--sapphire-light);
        color: var(--sapphire-light); padding: 7px 20px;
        border-radius: 2px; font-size: 11px; letter-spacing: 2px;
        text-transform: uppercase; text-decoration: none; transition: all 0.3s;
        }
        .nav-btn-outline:hover { background: var(--sapphire-light); color: #fff; }

        /* ===== HERO ===== */
        .hero {
        min-height: 100vh;
        background:
            linear-gradient(135deg, rgba(13,27,42,0.75) 0%, rgba(37,99,168,0.5) 100%),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&q=80') center/cover no-repeat fixed;
        display: flex; align-items: center; justify-content: center;
        text-align: center; padding: 0 24px; position: relative;
    }
    .hero::after {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 120px;
        background: linear-gradient(transparent, var(--cream));
    }
        .hero-content { position: relative; z-index: 1; }
        .hero-label {
            display: inline-block; border: 1px solid rgba(201,169,110,0.5);
            color: var(--gold); font-size: 10px; letter-spacing: 5px;
            text-transform: uppercase; padding: 6px 20px; margin-bottom: 28px;
            font-weight: 300;
            animation: fadeInDown 1s ease both;
        }
        .hero h1 {
            font-family: 'Libre Baskerville', serif;
            font-size: 80px; color: #fff; font-weight: 400;
            line-height: 1; letter-spacing: 6px; margin-bottom: 8px;
            animation: fadeInUp 1s ease 0.2s both;
        }
        .hero h1 em { color: var(--gold); font-style: italic; }
        .hero-sub {
            color: rgba(255,255,255,0.45); font-size: 11px; letter-spacing: 6px;
            text-transform: uppercase; margin-bottom: 28px;
            animation: fadeInUp 1s ease 0.3s both;
        }
        .hero-desc {
            color: rgba(255,255,255,0.7); font-size: 16px; font-weight: 300;
            max-width: 480px; margin: 0 auto 48px; line-height: 1.9;
            animation: fadeInUp 1s ease 0.4s both;
        }
        .hero-btns {
            display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.5s both;
        }
        .btn-hero-primary {
        background: linear-gradient(135deg, var(--sapphire), var(--sapphire-light));
        color: #fff; padding: 16px 40px;
        text-decoration: none; font-size: 11px; letter-spacing: 3px;
        text-transform: uppercase; border-radius: 2px; transition: all 0.3s;
        border: 1px solid var(--sapphire);
            }
        .btn-hero-primary:hover { background: transparent; color: var(--sapphire-light); border-color: var(--sapphire-light); }
        .btn-hero-secondary {
            border: 1px solid rgba(255,255,255,0.4); color: rgba(255,255,255,0.8);
            padding: 16px 40px; text-decoration: none; font-size: 11px;
            letter-spacing: 3px; text-transform: uppercase; border-radius: 2px;
            transition: all 0.3s;
        }
        .btn-hero-secondary:hover { border-color: var(--gold); color: var(--gold); }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute; bottom: 140px; left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: rgba(255,255,255,0.4); font-size: 10px; letter-spacing: 3px;
            text-transform: uppercase; z-index: 1;
            animation: bounce 2s infinite;
        }
        .scroll-indicator .line { width: 1px; height: 40px; background: linear-gradient(var(--gold), transparent); }

        /* ===== SECTION COMMON ===== */
        section { padding: 100px 80px; }
        .section-eyebrow {
        font-size: 10px; color: var(--sapphire);
        letter-spacing: 5px; text-transform: uppercase;
        font-weight: 400; margin-bottom: 12px;
        display: flex; align-items: center; gap: 12px;
    }
        .section-eyebrow::before, .section-eyebrow::after {
            content: ''; flex: 0 0 30px; height: 1px;
            background: var(--sapphire); opacity: 0.4;
        }
        .section-title { font-family: 'Libre Baskerville', serif; font-size: 42px; font-weight: 400; color: var(--dark); margin-bottom: 16px; line-height: 1.2; }
        .section-title em { font-style: italic; color: var(--brown-light); }
        .section-sub { font-size: 15px; color: var(--text-light); font-weight: 300; max-width: 500px; line-height: 1.8; margin-bottom: 56px; }

        /* ===== AVAILABILITY ===== */
        .availability {
        background: linear-gradient(135deg, var(--dark) 0%, var(--navy) 100%);
        padding: 80px;
        position: relative; overflow: hidden;
    }
        .availability::before {
        content: '☽';
        position: absolute; right: 80px; top: 50%; transform: translateY(-50%);
        font-size: 200px; color: rgba(37,99,168,0.08);
        pointer-events: none;
    }
        .avail-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(201,169,110,0.2);
            border-radius: 4px; padding: 40px;
        }
        .avail-fields { display: flex; gap: 0; align-items: stretch; flex-wrap: wrap; }
        .avail-field {
            flex: 1; min-width: 160px;
            border-right: 1px solid rgba(255,255,255,0.08);
            padding: 16px 24px;
        }
        .avail-field:last-of-type { border-right: none; }
        .avail-field label { display: block; font-size: 9px; color: rgba(255,255,255,0.4); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; }
        .avail-field input, .avail-field select {
            width: 100%; background: transparent; border: none;
            color: #fff; font-size: 15px; font-family: 'Source Sans 3', sans-serif;
            font-weight: 300; outline: none; padding: 4px 0;
        }
        .avail-field select option { background: var(--dark); }
        .btn-avail {
        background: linear-gradient(135deg, var(--sapphire), var(--sapphire-light));
        color: #fff; border: none;
        padding: 0 40px; font-size: 11px; letter-spacing: 3px;
        text-transform: uppercase; cursor: pointer; transition: all 0.3s;
        font-family: 'Source Sans 3', sans-serif; white-space: nowrap; margin-left: 0;
        border-radius: 0; min-width: 160px;
    }
        .btn-avail:hover { background: linear-gradient(135deg, var(--sapphire-light), var(--sapphire)); }
        .avail-form-wrap {
        display: flex; align-items: stretch; gap: 0;
        border: 1px solid rgba(201,169,110,0.25);
        border-radius: 2px; overflow: hidden;
    }
        /* Result card */
        #avail-result { margin-top: 32px; }
        .result-card {
        display: flex; justify-content: space-between; align-items: center;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(37,99,168,0.2);
        border-radius: 4px; padding: 24px 32px; margin-bottom: 12px;
        transition: all 0.3s; flex-wrap: wrap; gap: 16px;
    }
        .result-card:hover { border-color: var(--gold); background: rgba(201,169,110,0.06); }
        .btn-book-now {
            background: transparent; border: 1px solid var(--sapphire-light);
            color: var(--sapphire-light); padding: 10px 28px; font-size: 11px;
            letter-spacing: 2px; text-transform: uppercase; text-decoration: none;
            transition: all 0.3s; border-radius: 2px; white-space: nowrap;
        }
        .result-card .r-name { font-family: 'Libre Baskerville', serif; font-size: 18px; color: #fff; margin-bottom: 4px; }
        .result-card .r-info { font-size: 12px; color: rgba(255,255,255,0.4); letter-spacing: 1px; }
        .result-card .r-price { font-family: 'Libre Baskerville', serif; font-size: 24px; color: var(--gold); }
        .result-card .r-total { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 2px; }
        .btn-book-now { background: transparent; border: 1px solid var(--gold); color: var(--gold); padding: 10px 28px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; text-decoration: none; transition: all 0.3s; border-radius: 2px; white-space: nowrap; }
        .btn-book-now:hover { background: var(--sapphire-light); color: #fff; }
        .btn-book-disabled { border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.2); padding: 10px 28px; font-size: 11px; letter-spacing: 2px; border-radius: 2px; }

        /* ===== ROOMS ===== */
        .rooms-section { background: var(--cream); }
        .rooms-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2px; }
        .room-card { position: relative; overflow: hidden; cursor: pointer; }
        .room-card-img {
            height: 280px; overflow: hidden;
            background: linear-gradient(135deg, var(--brown), var(--dark));
        }
        .room-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .room-card:hover .room-card-img img { transform: scale(1.08); }
        .room-card-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(transparent 30%, rgba(13,27,42,0.92));
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 28px;
    }
        .room-card-name { font-family: 'Libre Baskerville', serif; font-size: 22px; color: #fff; margin-bottom: 4px; }
        .room-card-detail { font-size: 12px; color: rgba(255,255,255,0.6); margin-bottom: 8px; letter-spacing: 1px; }
        .room-card-price { font-size: 16px; color: var(--gold); }
        .room-card-price span { font-size: 12px; color: rgba(255,255,255,0.4); }

        /* ===== MENU ===== */
        .menu-section { background: #fff; }
        .menu-tabs {
            display: flex; gap: 0; margin-bottom: 48px;
            border-bottom: 2px solid var(--cream-dark);
            justify-content: center;
        }
        .menu-tab {
            padding: 14px 32px; font-size: 11px; letter-spacing: 2px;
            text-transform: uppercase; cursor: pointer; color: var(--text-light);
            border-bottom: 3px solid transparent; margin-bottom: -2px;
            transition: all 0.3s; font-family: 'Source Sans 3', sans-serif;
        }
        .menu-tab.active, .menu-tab:hover {
            color: var(--sapphire);
            border-bottom-color: var(--sapphire);
        }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px; }
        .menu-card {
            border: 1px solid var(--cream-dark); border-radius: 4px;
            overflow: hidden; transition: all 0.3s;
        }
        .menu-card:hover { box-shadow: 0 8px 32px rgba(107,76,42,0.12); transform: translateY(-3px); }
        .menu-card-img { height: 160px; background: var(--cream-dark); overflow: hidden; position: relative; }
        .menu-card-img img { width: 100%; height: 100%; object-fit: cover; }
        .menu-card-icon-wrap {
            width: 100%; height: 100%; display: flex; align-items: center;
            justify-content: center; background: var(--cream);
        }
        .menu-card-icon-wrap i { font-size: 32px; color: var(--gold); opacity: 0.5; }
        .menu-card-body { padding: 20px; }
        .menu-card-cat { font-size: 9px; color: var(--gold); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 6px; }
        .menu-card-name { font-family: 'Libre Baskerville', serif; font-size: 17px; color: var(--dark); margin-bottom: 8px; }
        .menu-card-price { font-size: 15px; color: var(--brown-light); font-weight: 700; }
        .btn-order-menu {
        display: inline-block; margin-top: 12px; font-size: 10px;
        letter-spacing: 2px; text-transform: uppercase; color: var(--sapphire);
        border: 1px solid var(--sapphire); padding: 5px 14px; border-radius: 2px;
        text-decoration: none; transition: all 0.3s;
        }
        .btn-order-menu:hover { background: var(--sapphire); color: #fff; }

        /* ===== REVIEWS ===== */
        .reviews-section { background: var(--cream); }
        .reviews-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 28px; }
        .review-card {
        background: #fff; border-radius: 4px; padding: 32px;
        border: 1px solid var(--cream-dark); position: relative;
        transition: all 0.3s;
        border-top: 3px solid var(--sapphire);
        }
        .review-card:hover { box-shadow: 0 8px 32px rgba(37,99,168,0.1); transform: translateY(-3px); }
        .review-card::before {
            content: '"'; font-family: 'Libre Baskerville', serif;
            font-size: 80px; color: var(--sapphire-pale); position: absolute;
            top: 16px; left: 24px; line-height: 1; opacity: 0.8;
        }
        .review-stars { color: var(--gold); font-size: 13px; margin-bottom: 16px; letter-spacing: 2px; }
        .review-text { font-size: 14px; color: var(--text-light); line-height: 1.8; margin-bottom: 20px; font-style: italic; font-weight: 300; padding-top: 16px; }
        .review-author { font-size: 13px; color: var(--dark); font-weight: 700; letter-spacing: 1px; }
        .review-date { font-size: 11px; color: var(--text-light); margin-top: 2px; letter-spacing: 1px; }

        /* ===== CONTACT ===== */
        .contact-section {
        background: var(--dark);
        background-image:
            linear-gradient(135deg, rgba(13,27,42,0.95) 0%, rgba(26,47,78,0.95) 100%),
            url('https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=1920&q=80');
        background-size: cover; background-position: center;
        background-attachment: fixed; position: relative;
    }
        .contact-section::before { content: ''; position: absolute; inset: 0; background: rgba(44,24,16,0.88); }
        .contact-inner { position: relative; z-index: 1; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; margin-top: 56px; }
        .contact-item { display: flex; gap: 20px; margin-bottom: 32px; align-items: flex-start; }
        .contact-icon {
            width: 44px; height: 44px;
            background: rgba(37,99,168,0.15);
            border: 1px solid rgba(37,99,168,0.3);
            border-radius: 2px; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
        }
        .contact-icon i { color: var(--sapphire-light); font-size: 16px; }
        .contact-label { font-size: 9px; color: rgba(255,255,255,0.35); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 6px; }
        .contact-value { font-size: 15px; color: rgba(255,255,255,0.85); font-weight: 300; line-height: 1.6; }
        .rules-box { border: 1px solid rgba(201,169,110,0.2); border-radius: 4px; padding: 32px; }
        .rules-title { font-family: 'Libre Baskerville', serif; font-size: 20px; color: #fff; margin-bottom: 20px; }
        .rule-item { display: flex; gap: 12px; margin-bottom: 16px; align-items: flex-start; }
        .rule-dot { width: 6px; height: 6px; background: var(--sapphire-light); border-radius: 50%; flex-shrink: 0; margin-top: 7px; }
        .rule-text { font-size: 13px; color: rgba(255,255,255,0.6); line-height: 1.7; font-weight: 300; }

        /* ===== FOOTER ===== */
        footer {
        background: var(--dark);
        padding: 40px 80px;
        display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid rgba(37,99,168,0.2);
    }
        .footer-brand { font-family: 'Libre Baskerville', serif; font-size: 18px; color: var(--gold); letter-spacing: 3px; }
        .footer-copy { font-size: 12px; color: rgba(255,255,255,0.25); letter-spacing: 1px; }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes bounce { 0%,100% { transform:translateX(-50%) translateY(0); } 50% { transform:translateX(-50%) translateY(8px); } }

        /* Fade-in on scroll */
        .fade-in { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* ===== BURGER MENU ===== */
        .burger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            z-index: 1001;
        }
        .burger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--gold);
            transition: all 0.3s;
            border-radius: 2px;
        }
        .burger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .burger.open span:nth-child(2) { opacity: 0; }
        .burger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 72px; left: 0; right: 0;
            background: rgba(44,24,16,0.98);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(201,169,110,0.15);
            padding: 24px 32px;
            z-index: 999;
            flex-direction: column;
            gap: 4px;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: color 0.2s;
            font-family: 'Source Sans 3', sans-serif;
        }
        .mobile-menu a:hover { color: var(--gold); }
        .mobile-menu a:last-child { border-bottom: none; }
        .mobile-menu .mobile-divider {
            height: 1px;
            background: rgba(201,169,110,0.2);
            margin: 8px 0;
        }
        .mobile-menu .mobile-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .mobile-menu .mobile-profile-img {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            overflow: hidden;
            flex-shrink: 0;
        }
        .mobile-menu .mobile-profile-name {
            color: #fff;
            font-size: 14px;
        }
        .mobile-menu .mobile-profile-role {
            color: var(--gold);
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            nav { padding: 0 24px; }
            .nav-center { display: none; }
            .nav-right { display: none; }
            .burger { display: flex; }
            section { padding: 60px 24px; }
            .hero h1 { font-size: 42px; }
            .hero-label { font-size: 9px; }
            .hero-desc { font-size: 14px; }
            .contact-grid { grid-template-columns: 1fr; gap: 32px; }
            .avail-form-wrap { flex-direction: column; }
            .avail-fields { flex-direction: column; }
            .avail-field { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
            .btn-avail { padding: 16px; margin-left: 0; }
            footer { flex-direction: column; text-align: center; gap: 8px; padding: 32px 24px; }
            .availability { padding: 60px 24px; }
            .rooms-grid { grid-template-columns: 1fr; gap: 16px; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav id="navbar">
    <div class="nav-brand">
        <div>
            <div class="logo">☽ LUNAR</div>
            <div class="tagline">Hotel & Resort</div>
        </div>
    </div>

    {{-- Desktop menu tengah --}}
    <div class="nav-center">
        <a href="#rooms">Kamar</a>
        <a href="#availability">Reservasi</a>
        <a href="#menu">Restoran</a>
        <a href="#reviews">Review</a>
        <a href="#contact">Kontak</a>
    </div>

    {{-- Desktop kanan --}}
    <div class="nav-right">
        @auth
        <div class="nav-divider"></div>
        <a href="/profile" class="nav-avatar">
            <div class="nav-avatar-img">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}">
                @else
                    <div class="nav-avatar-initial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
            </div>
            <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
        </a>
        @if(auth()->user()->hasRole('customer'))
            <a href="/my-bookings" class="nav-link">My Booking</a>
        @else
            <a href="/dashboard" class="nav-btn-outline">Dashboard</a>
        @endif
        @else
        <a href="/login" class="nav-link">Masuk</a>
        <a href="/register" class="nav-btn-outline">Daftar</a>
        @endauth
    </div>

    {{-- Burger button --}}
    <div class="burger" id="burger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mobile-menu" id="mobileMenu">
    @auth
    <div class="mobile-profile">
        <div class="mobile-profile-img">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%;height:100%;object-fit:cover">
            @else
                <div style="width:100%;height:100%;background:var(--gold);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div>
            <div class="mobile-profile-name">{{ auth()->user()->name }}</div>
            <div class="mobile-profile-role">{{ ucfirst(auth()->user()->getRoleNames()->first()) }}</div>
        </div>
    </div>
    @endauth

    <a href="#rooms" onclick="closeMenu()">Kamar</a>
    <a href="#availability" onclick="closeMenu()">Reservasi</a>
    <a href="#menu" onclick="closeMenu()">Restoran</a>
    <a href="#reviews" onclick="closeMenu()">Review</a>
    <a href="#contact" onclick="closeMenu()">Kontak</a>

    <div class="mobile-divider"></div>

    @auth
        <a href="/profile" onclick="closeMenu()">
            <i class="fas fa-user-cog" style="margin-right:8px;color:var(--gold)"></i> Edit Profil
        </a>
        @if(auth()->user()->hasRole('customer'))
            <a href="/my-bookings" onclick="closeMenu()">
                <i class="fas fa-calendar-check" style="margin-right:8px;color:var(--gold)"></i> My Booking
            </a>
        @else
            <a href="/dashboard" onclick="closeMenu()">
                <i class="fas fa-chart-line" style="margin-right:8px;color:var(--gold)"></i> Dashboard
            </a>
        @endif
        <div class="mobile-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:100%;background:transparent;border:none;color:rgba(255,100,100,0.7);font-size:12px;letter-spacing:2px;text-transform:uppercase;padding:14px 0;cursor:pointer;text-align:left;font-family:'Source Sans 3',sans-serif">
                <i class="fas fa-sign-out-alt" style="margin-right:8px"></i> Keluar
            </button>
        </form>
    @else
        <a href="/login" onclick="closeMenu()">
            <i class="fas fa-sign-in-alt" style="margin-right:8px;color:var(--gold)"></i> Masuk
        </a>
        <a href="/register" onclick="closeMenu()">
            <i class="fas fa-user-plus" style="margin-right:8px;color:var(--gold)"></i> Daftar
        </a>
    @endauth
</div>

{{-- HERO --}}
<section class="hero">
    <div class="hero-content">
        <div class="hero-label">Selamat Datang di Lunar Hotel</div>
        <h1>Where <em>Luxury</em><br>Meets Soul</h1>
        <div class="hero-sub">Jakarta, Indonesia · Est. 2024</div>
        <p class="hero-desc">Rasakan kehangatan keramahan klasik dalam balutan kemewahan modern. Setiap sudut menceritakan sebuah kisah.</p>
        <div class="hero-btns">
            <a href="#availability" class="btn-hero-primary">Reservasi Sekarang</a>
            <a href="#rooms" class="btn-hero-secondary">Jelajahi Kamar</a>
        </div>
    </div>
    <div class="scroll-indicator">
        <div class="line"></div>
        <span>Scroll</span>
    </div>
</section>

{{-- CEK KETERSEDIAAN --}}
<section class="availability" id="availability">
    <div class="fade-in">
        <div class="section-eyebrow">Reservasi</div>
        <div class="section-title" style="color:#fff">Cek <em>Ketersediaan</em></div>
        <div class="section-sub" style="color:rgba(255,255,255,0.45)">Temukan kamar sempurna untuk momen spesial Anda.</div>
    </div>
    <div class="avail-box fade-in">
        <div class="avail-form-wrap">
            <div class="avail-fields" style="flex:1">
                <div class="avail-field">
                    <label>Check-in</label>
                    <input type="date" id="l_check_in" min="{{ date('Y-m-d') }}">
                </div>
                <div class="avail-field">
                    <label>Check-out</label>
                    <input type="date" id="l_check_out">
                </div>
                <div class="avail-field">
                    <label>Tamu</label>
                    <select id="l_guests">
                        <option value="1">1 Tamu</option>
                        <option value="2">2 Tamu</option>
                        <option value="3">3 Tamu</option>
                        <option value="4">4 Tamu</option>
                    </select>
                </div>
            </div>
            <button class="btn-avail" onclick="searchRooms()">
                <i class="fas fa-search" style="margin-right:8px"></i> Cari Kamar
            </button>
        </div>
    </div>
    <div id="avail-result"></div>
</section>

{{-- KAMAR --}}
<section class="rooms-section" id="rooms">
    <div style="text-align:center;margin-bottom:56px" class="fade-in">
        <div class="section-eyebrow" style="justify-content:center">Akomodasi</div>
        <div class="section-title" style="text-align:center">Pilihan <em>Kamar</em> Kami</div>
    </div>
    <div class="rooms-grid fade-in">
        @forelse($roomTypes as $type)
        <div class="room-card">
            <div class="room-card-img">
                @if($type->image)
                    <img src="{{ asset('storage/' . $type->image) }}" alt="{{ $type->name }}">
                @else
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--brown),var(--dark));display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-door-open" style="font-size:48px;color:rgba(201,169,110,0.3)"></i>
                    </div>
                @endif
            </div>
            <div class="room-card-overlay">
                <div class="room-card-name">{{ $type->name }}</div>
                <div class="room-card-detail">
                    <i class="fas fa-user" style="margin-right:4px"></i> Maks {{ $type->capacity }} orang
                    &nbsp;·&nbsp; {{ $type->rooms_count }} kamar tersedia
                </div>
                <div class="room-card-price">
                    Rp {{ number_format($type->base_price, 0, ',', '.') }}
                    <span>/ malam</span>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-light)">
            Informasi kamar segera hadir.
        </div>
        @endforelse
    </div>
</section>

{{-- MENU --}}
<section class="menu-section" id="menu">
    <div style="text-align:center;margin-bottom:16px" class="fade-in">
        <div class="section-eyebrow" style="justify-content:center">Kuliner</div>
        <div class="section-title" style="text-align:center">Menu <em>Restoran</em></div>
        <div class="section-sub" style="text-align:center;margin:0 auto">Sajian istimewa dari dapur terbaik kami untuk melengkapi pengalaman menginap Anda.</div>
    </div>

    @php $allCategories = $menus->keys(); @endphp
    <div class="menu-tabs fade-in" style="justify-content:center">
        @foreach($allCategories as $i => $cat)
        <div class="menu-tab {{ $i === 0 ? 'active' : '' }}" onclick="showCategory('{{ $cat }}', this)">
            {{ $cat }}
        </div>
        @endforeach
    </div>

    @foreach($menus as $category => $items)
    <div class="menu-grid menu-category fade-in"
        data-cat="{{ $category }}"
        id="cat-{{ Str::slug($category) }}"
        style="{{ !$loop->first ? 'display:none' : '' }}">
        @foreach($items as $menu)
        <div class="menu-card">
            <div class="menu-card-img">
                @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                @else
                    <div class="menu-card-icon-wrap">
                        @if($category === 'Makanan') <i class="fas fa-utensils"></i>
                        @elseif($category === 'Minuman') <i class="fas fa-coffee"></i>
                        @elseif($category === 'Dessert') <i class="fas fa-ice-cream"></i>
                        @else <i class="fas fa-cookie"></i>
                        @endif
                    </div>
                @endif
                @if(!$menu->is_available)
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center">
                    <span style="background:#c0392b;color:#fff;padding:4px 12px;font-size:10px;letter-spacing:2px;text-transform:uppercase">Habis</span>
                </div>
                @endif
            </div>
            <div class="menu-card-body">
                <div class="menu-card-cat">{{ $menu->category }}</div>
                <div class="menu-card-name">{{ $menu->name }}</div>
                <div class="menu-card-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                @if($menu->is_available)
                    @auth
                        @if(auth()->user()->hasRole('customer'))
                        <a href="/customer/orders/create" class="btn-order-menu">+ Pesan</a>
                        @endif
                    @else
                    <a href="/register" class="btn-order-menu">+ Pesan</a>
                    @endauth
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</section>

{{-- REVIEW --}}
<section class="reviews-section" id="reviews">
    <div style="text-align:center;margin-bottom:56px" class="fade-in">
        <div class="section-eyebrow" style="justify-content:center">Testimoni</div>
        <div class="section-title" style="text-align:center">Kata <em>Tamu</em> Kami</div>
    </div>

    @if($reviews->count() > 0)
    <div class="reviews-grid fade-in">
        @foreach($reviews as $review)
        <div class="review-card">
            <div class="review-stars">
                @for($i = 1; $i <= 5; $i++)
                ★
                @endfor
            </div>
            <div class="review-text">{{ $review->comment }}</div>
            <div class="review-author">{{ $review->user->name }}</div>
            <div class="review-date">{{ $review->created_at->format('d M Y') }}</div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:60px 0" class="fade-in">
        <div style="font-family:'Libre Baskerville',serif;font-size:60px;color:var(--cream-dark);margin-bottom:16px">☽</div>
        <div style="color:var(--text-light);font-size:14px;margin-bottom:16px">Jadilah tamu pertama yang memberikan ulasan!</div>
        <a href="/register" style="color:var(--gold);font-size:12px;letter-spacing:2px;text-transform:uppercase;text-decoration:none;border-bottom:1px solid var(--gold);padding-bottom:2px">Daftar Sekarang →</a>
    </div>
    @endif
</section>

{{-- KONTAK --}}
<section class="contact-section" id="contact">
    <div class="contact-inner">
        <div style="text-align:center" class="fade-in">
            <div class="section-eyebrow" style="justify-content:center;color:var(--gold)">Hubungi Kami</div>
            <div class="section-title" style="color:#fff;text-align:center">Informasi <em style="color:var(--gold)">& Lokasi</em></div>
        </div>
        <div class="contact-grid fade-in">
            <div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="contact-label">Alamat</div>
                        <div class="contact-value">Jl. Bulan Purnama No. 1<br>Jakarta, Indonesia</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <div class="contact-label">Telepon</div>
                        <div class="contact-value">+62 21 1234 5678</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="contact-label">Email</div>
                        <div class="contact-value">info@lunarhotel.com</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="contact-label">Jam Operasional</div>
                        <div class="contact-value">Check-in: 14.00 WIB<br>Check-out: 12.00 WIB</div>
                    </div>
                </div>
            </div>
            <div class="rules-box">
                <div class="rules-title">Ketentuan Tamu</div>
                <div class="rule-item">
                    <div class="rule-dot"></div>
                    <div class="rule-text">Tamu wajib berusia minimal <strong style="color:var(--gold)">17 tahun</strong> dan memiliki KTP/identitas valid saat check-in.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-dot"></div>
                    <div class="rule-text">Tamu di bawah 17 tahun wajib didampingi orang tua atau wali yang sah.</div>
                </div>
                <div class="rule-item">
                    <div class="rule-dot"></div>
                    <div class="rule-text">Pembatalan reservasi dapat dilakukan maksimal H-1 sebelum check-in.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-brand">☽ LUNAR HOTEL</div>
    <div class="footer-copy">© {{ date('Y') }} Lunar Hotel · All rights reserved</div>
</footer>

<script>
const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Fade in on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

// Menu tabs
function showCategory(cat, el) {
    document.querySelectorAll('.menu-category').forEach(d => d.style.display = 'none');
    document.querySelectorAll('.menu-tab').forEach(t => t.classList.remove('active'));
    const target = document.querySelector('.menu-category[data-cat="' + cat + '"]');
    if (target) target.style.display = 'grid';
    el.classList.add('active');
}

// Burger menu
function toggleMenu() {
    document.getElementById('burger').classList.toggle('open');
    document.getElementById('mobileMenu').classList.toggle('open');
}

function closeMenu() {
    document.getElementById('burger').classList.remove('open');
    document.getElementById('mobileMenu').classList.remove('open');
}

document.addEventListener('click', function(e) {
    const burger = document.getElementById('burger');
    const menu   = document.getElementById('mobileMenu');
    if (burger && menu && !burger.contains(e.target) && !menu.contains(e.target)) {
        closeMenu();
    }
});

// Search rooms
async function searchRooms() {
    const checkIn  = document.getElementById('l_check_in').value;
    const checkOut = document.getElementById('l_check_out').value;
    if (!checkIn || !checkOut) { alert('Pilih tanggal dulu.'); return; }

    const result = document.getElementById('avail-result');
    result.innerHTML = '<div style="color:rgba(255,255,255,0.4);font-family:Source Sans 3;font-size:13px;padding:20px;text-align:center;letter-spacing:2px">MENCARI KAMAR TERSEDIA...</div>';

    const res  = await fetch('/landing/check-availability?' + new URLSearchParams({ check_in: checkIn, check_out: checkOut }));
    const data = await res.json();

    if (!data.length) {
        result.innerHTML = '<div style="color:#e07c7c;font-family:Source Sans 3;font-size:13px;padding:20px;text-align:center;border:1px solid rgba(200,100,100,0.2);border-radius:4px;letter-spacing:1px">Tidak ada kamar tersedia di tanggal ini.</div>';
        return;
    }

    let html = '';
    data.forEach(room => {
        const available = room.available > 0;
        const bookUrl = isLoggedIn
            ? `/customer/bookings/create?room_type_id=${room.id}&check_in=${checkIn}&check_out=${checkOut}`
            : `/register`;

        html += `
        <div class="result-card">
            <div>
                <div class="r-name">${room.name}</div>
                <div class="r-info">
                    <i class="fas fa-user" style="margin-right:4px;color:var(--gold)"></i> Maks ${room.capacity} orang
                    &nbsp;·&nbsp;
                    ${available ? `<span style="color:#7ed87e">${room.available} kamar tersedia</span>` : '<span style="color:#e07c7c">Penuh</span>'}
                </div>
            </div>
            <div style="text-align:right">
                <div class="r-price">Rp ${room.price_per_night.toLocaleString('id-ID')}<span style="font-size:12px;color:rgba(255,255,255,0.3);margin-left:4px">/malam</span></div>
                <div class="r-total">Total ${room.nights} malam: Rp ${room.total_price.toLocaleString('id-ID')}</div>
            </div>
            ${available
                ? `<a href="${bookUrl}" class="btn-book-now">${isLoggedIn ? 'Pesan Sekarang' : 'Daftar & Pesan'}</a>`
                : `<div class="btn-book-disabled">Tidak Tersedia</div>`
            }
        </div>`;
    });
    result.innerHTML = html;
}
</script>
</body>
</html>