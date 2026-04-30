@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Selamat datang, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-400">Dashboard inventaris dan peminjaman barang sekolah</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-6 bg-gradient-to-br from-[#d2a14a]/10 to-[#d2a14a]/5 border border-[#d2a14a]/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Total Barang Tersedia</p>
            <p class="text-3xl font-bold text-[#d2a14a]">{{ $totalStock ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Unit barang di sistem</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-[#e6c384]/10 to-[#e6c384]/5 border border-[#e6c384]/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Barang Saya Pinjam</p>
            <p class="text-3xl font-bold text-[#e6c384]">{{ $borrowedCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Sedang dipinjam</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-gray-400/10 to-gray-400/5 border border-gray-400/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Riwayat Peminjaman</p>
            <p class="text-3xl font-bold text-gray-300">{{ $historyCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Total peminjaman</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-6">
        <div class="p-6 bg-black/30 border border-[#d2a14a]/20 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="/units" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📦 Lihat Inventaris
                </a>
                <a href="/history" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📋 Riwayat Peminjaman Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
