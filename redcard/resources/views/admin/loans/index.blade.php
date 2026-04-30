@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Riwayat Peminjaman</h1>
        <p class="text-gray-400">Pantau peminjaman yang sedang berjalan dan yang sudah dikembalikan.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/25 bg-emerald-950/25 px-4 py-3 text-sm text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/35 bg-red-950/40 px-4 py-3 text-sm leading-snug text-red-100/95">
            <p class="font-semibold mb-1">Periksa input:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
        <table class="min-w-full text-sm">
            <thead class="bg-[#0d0a07]/70">
                <tr class="text-left text-gray-300">
                    <th class="px-4 py-3 font-semibold">Peminjam</th>
                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                    <th class="px-4 py-3 font-semibold">Waktu</th>
                    <th class="px-4 py-3 font-semibold">Lokasi Ambil</th>
                    <th class="px-4 py-3 font-semibold">Unit</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold w-[260px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                @forelse ($loans as $loan)
                    <tr class="align-top">
                        <td class="px-4 py-3">
                            <p class="text-[#f4ead8] font-semibold">{{ $loan->user?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->user?->username ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            <p>{{ optional($loan->loan_date)->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">Due: {{ optional($loan->due_date)->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            {{ $loan->start_time }} - {{ $loan->end_time }}
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            {{ $loan->pickupLocation?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            <div class="space-y-1">
                                @foreach ($loan->items as $item)
                                    <p class="text-sm">
                                        {{ $item->unit?->name ?? 'Unit' }}
                                        <span class="text-xs text-gray-400">({{ $item->unit?->code ?? '-' }})</span>
                                    </p>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($loan->status === 'returned')
                                <span class="inline-flex items-center rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-200">
                                    Returned
                                </span>
                                <p class="mt-2 text-xs text-gray-400">
                                    {{ $loan->return_date ? $loan->return_date->format('d M Y H:i') : '' }}
                                </p>
                                @if (($loan->fine ?? 0) > 0)
                                    <p class="text-xs text-red-200">Denda: Rp {{ number_format($loan->fine, 0, ',', '.') }}</p>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-full border border-[#c9a255]/25 bg-[#d2a14a]/10 px-3 py-1 text-xs font-bold text-[#e6c384]">
                                    Borrowed
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($loan->status === 'borrowed')
                                <form method="POST" action="/admin/loans/{{ $loan->id }}/return" class="space-y-3">
                                    @csrf

                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2">
                                            Lokasi Pengembalian
                                        </label>
                                        <select
                                            name="return_location_id"
                                            required
                                            class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        >
                                            <option value="" selected disabled>Pilih lokasi</option>
                                            @foreach ($locations as $loc)
                                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2">
                                                Kondisi
                                            </label>
                                            <input
                                                name="condition"
                                                type="text"
                                                placeholder="Baik / Rusak ringan / ..."
                                                class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2">
                                                Catatan (opsional)
                                            </label>
                                            <input
                                                name="review"
                                                type="text"
                                                placeholder="Catatan pengembalian..."
                                                class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                            >
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] hover:bg-[#d2a14a]/25 transition"
                                        onclick="return confirm('Tandai pinjaman ini sebagai sudah dikembalikan?')"
                                    >
                                        Proses Pengembalian
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-gray-400">—</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

