@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Admin Dashboard</h1>
        <p class="text-gray-400">Kelola seluruh sistem aset sekolah</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-6 bg-gradient-to-br from-[#d2a14a]/10 to-[#d2a14a]/5 border border-[#d2a14a]/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Total Units</p>
            <p class="text-3xl font-bold text-[#d2a14a]">{{ $total_units ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Inventaris tersedia</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-[#e6c384]/10 to-[#e6c384]/5 border border-[#e6c384]/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Sedang Dipinjam</p>
            <p class="text-3xl font-bold text-[#e6c384]">{{ $borrowed ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Status pinjaman aktif</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-gray-400/10 to-gray-400/5 border border-gray-400/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Pengguna Terdaftar</p>
            <p class="text-3xl font-bold text-gray-300">{{ $users ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">User aktif sistem</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-6">
        <div class="p-6 bg-black/30 border border-[#d2a14a]/20 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-4">Management</h3>
            <div class="space-y-3">
                <a href="/admin/units" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📦 Kelola Unit Barang
                </a>
                <a href="/admin/loans" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📋 Riwayat Peminjaman
                </a>
                <a href="/admin/users" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    👥 Kelola Pengguna
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
