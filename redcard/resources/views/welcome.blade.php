@extends('layouts.luxury')

@section('content')
    <p class="luxury-kicker">Elegant. Fast. Accurate.</p>
    <h1 class="luxury-title">Kelola aset sekolah dengan tampilan yang mewah dan profesional.</h1>
    <p class="luxury-lead">
        RedCard membantu tim Anda memantau stok, peminjaman, dan histori barang dalam satu dashboard modern
        yang rapi, cepat, dan mudah dipakai di desktop maupun mobile.
    </p>

    <div class="luxury-cta-row">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="luxury-btn luxury-btn-solid">Masuk Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="luxury-btn luxury-btn-solid">Login Pengguna</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="luxury-btn luxury-btn-ghost">Daftar</a>
                @endif
                @if (Route::has('admin.login'))
                    <a href="{{ route('admin.login') }}" class="luxury-btn luxury-btn-ghost">Login Admin</a>
                @endif
            @endauth
        @endif
    </div>

    <div class="luxury-stats">
        <article class="luxury-stat reveal-up delay-1">
            <p class="luxury-stat-value">Realtime</p>
            <p class="luxury-stat-label">Status aset dan stok</p>
        </article>
        <article class="luxury-stat reveal-up delay-2">
            <p class="luxury-stat-value">Audit-ready</p>
            <p class="luxury-stat-label">Riwayat peminjaman lengkap</p>
        </article>
        <article class="luxury-stat reveal-up delay-3">
            <p class="luxury-stat-value">Role-based</p>
            <p class="luxury-stat-label">Akses admin dan petugas</p>
        </article>
    </div>
@endsection
