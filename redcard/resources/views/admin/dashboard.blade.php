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
                <a href="/admin/locations" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📍 Kelola Lokasi Pengambilan
                </a>
            </div>
        </div>

        <div class="p-6 bg-black/30 border border-[#d2a14a]/20 rounded-lg">
            <div class="mb-4 flex items-end justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-1">Riwayat Peminjaman Terbaru</h3>
                </div>
                <a
                    href="/admin/loans"
                    class="inline-flex rounded-lg border border-[#d2a14a]/40 bg-[#d2a14a]/10 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/20"
                >
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
                <table class="w-full table-fixed text-sm">
                    <thead class="bg-[#0d0a07]/70">
                        <tr class="text-center text-gray-300">
                            <th class="w-[22%] px-3 py-3 font-semibold">Peminjam</th>
                            <th class="w-[18%] px-3 py-3 font-semibold">Pinjam</th>
                            <th class="w-[20%] px-3 py-3 font-semibold">Pengembalian</th>
                            <th class="w-[20%] px-3 py-3 font-semibold">Status</th>
                            <th class="w-[20%] px-3 py-3 font-semibold">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                        @forelse ($recentLoans as $loan)
                            <tr class="text-center hover:bg-white/[0.03] transition">
                                <td class="px-3 py-3 text-left">
                                    <p class="text-[#f4ead8] font-semibold">{{ $loan->user?->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ $loan->user?->username ?? '' }}</p>
                                </td>
                                <td class="px-3 py-3 text-gray-200 whitespace-nowrap">
                                    {{ optional($loan->loan_date)->format('d M Y') }}
                                </td>
                                <td class="px-3 py-3 text-gray-200 whitespace-nowrap">
                                    {{ $loan->return_date ? $loan->return_date->format('d M Y H:i') : '—' }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if ($loan->status === 'returned')
                                        <span class="inline-flex items-center rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-200">
                                            Returned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-[#c9a255]/25 bg-[#d2a14a]/10 px-3 py-1 text-xs font-bold text-[#e6c384]">
                                            Borrowed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-gray-200 whitespace-nowrap">
                                    @if (($loan->fine ?? 0) > 0)
                                        <span class="text-red-200 font-semibold">
                                            Rp {{ number_format($loan->fine ?? 0, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Rp 0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center text-gray-400">
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-6 bg-black/30 border border-[#d2a14a]/20 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-1">Chat Pengembalian Terbaru</h3>

            <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
                <table class="w-full table-fixed text-sm">
                    <thead class="bg-[#0d0a07]/70">
                        <tr class="text-center text-gray-300">
                            <th class="w-[20%] px-3 py-3 font-semibold">Waktu</th>
                            <th class="w-[20%] px-3 py-3 font-semibold">User</th>
                            <th class="w-[10%] px-3 py-3 font-semibold">Loan</th>
                            <th class="w-[35%] px-3 py-3 font-semibold">Pesan</th>
                            <th class="w-[15%] px-3 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                        @forelse ($returnChats as $chat)
                            <tr class="align-top text-center hover:bg-white/[0.03] transition">
                                <td class="px-3 py-3 text-gray-200 whitespace-nowrap">
                                    {{ $chat->created_at?->format('d M Y H:i') }}
                                </td>
                                <td class="px-3 py-3 text-left text-[#f4ead8]">
                                    <p class="font-semibold">{{ $chat->user?->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-400">{{ $chat->user?->username ? '@'.$chat->user?->username : ($chat->user?->email ?? '-') }}</p>
                                </td>
                                <td class="px-3 py-3 text-gray-200 whitespace-nowrap">
                                    {{ $chat->loan_id ? '#'.$chat->loan_id : '-' }}
                                </td>
                                <td class="px-3 py-3 text-left text-gray-200 break-words">
                                    {{ $chat->message }}
                                </td>
                                <td class="px-3 py-3">
                                    <a
                                        href="/admin/loans"
                                        class="inline-flex rounded-lg border border-[#d2a14a]/40 bg-[#d2a14a]/10 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/20"
                                    >
                                        Buka Loan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center text-gray-400">
                                    Belum ada chat pengembalian dari user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
