<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'RedCard') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="luxury-page">
    <div class="luxury-bg-shape luxury-bg-shape-1" aria-hidden="true"></div>
    <div class="luxury-bg-shape luxury-bg-shape-2" aria-hidden="true"></div>

    <header class="luxury-nav-wrap">
        <nav class="luxury-nav">
            <div class="luxury-brand">
                <span class="luxury-brand-mark">RC</span>
                <div>
                    <p class="luxury-brand-name">RedCard Asset System</p>
                    <p class="luxury-brand-sub">Inventory and Loan Management</p>
                </div>
            </div>

            @auth
                <div class="luxury-nav-actions">
                    <span class="luxury-nav-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="luxury-btn luxury-btn-ghost">Keluar</button>
                    </form>
                </div>
            @endauth
        </nav>
    </header>

    <main class="luxury-hero @if(request()->routeIs('login', 'admin.login', 'register')) luxury-hero--auth @endif">
        <div class="luxury-card reveal-up @if(request()->routeIs('login', 'admin.login', 'register')) luxury-card--auth @endif">
            @yield('content')
        </div>
    </main>

    @stack('body_end')
</body>
</html>
