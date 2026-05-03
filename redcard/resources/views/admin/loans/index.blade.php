@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Riwayat Peminjaman</h1>
        </div>
        <div class="no-print flex flex-wrap items-center gap-2">
            <a
                href="/admin"
                class="text-sm font-semibold text-[#e6c384] hover:text-[#f0d9a6] hover:underline underline-offset-4"
            >
                Kembali ke Dashboard
            </a>
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center justify-center rounded-xl border border-[#c9a255]/45 bg-[#d2a14a]/15 px-4 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25"
            >
                Cetak Riwayat
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="no-print mb-6 rounded-xl border border-emerald-500/25 bg-emerald-950/25 px-4 py-3 text-sm text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="no-print mb-6 rounded-xl border border-red-500/35 bg-red-950/40 px-4 py-3 text-sm leading-snug text-red-100/95">
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
                    <th class="px-4 py-3 font-semibold">Tanggal Pinjam</th>
                    <th class="px-4 py-3 font-semibold">Tanggal Pengembalian</th>
                    <th class="px-4 py-3 font-semibold">Waktu</th>
                    <th class="px-4 py-3 font-semibold">Lokasi Ambil</th>
                    <th class="px-4 py-3 font-semibold">Unit</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold w-[260px] no-print">Aksi</th>
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
                        <td class="px-4 py-3 text-gray-200 whitespace-nowrap">
                            {{ $loan->return_date ? $loan->return_date->format('d M Y H:i') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            {{ $loan->start_time }} - {{ $loan->end_time }}
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            {{ $loan->pickupLocation?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-200">
                            @php
                                $groupedLoanItems = collect($loan->items ?? [])
                                    ->map(function ($item) {
                                        $unit = $item->unit;
                                        $categories = collect($unit?->categories ?? [])->sortBy('name')->values();
                                        $unitName = strtoupper((string) ($unit?->name ?? ''));

                                        $primaryCategory = $categories->first(function ($category) use ($unitName) {
                                            $categoryName = strtoupper((string) ($category->name ?? ''));
                                            return $categoryName !== '' && str_contains($unitName, $categoryName);
                                        }) ?? $categories->first();

                                        return [
                                            'category' => strtoupper((string) ($primaryCategory->name ?? 'Tanpa Kategori')),
                                            'item' => $item,
                                        ];
                                    })
                                    ->groupBy('category')
                                    ->sortKeys();
                            @endphp

                            <div class="space-y-2">
                                @forelse ($groupedLoanItems as $categoryName => $rows)
                                    <div class="rounded-lg border border-[#3a2b18] bg-black/25 p-2">
                                        <div class="mb-1 flex items-center justify-between gap-2">
                                            <p class="text-[11px] font-bold uppercase tracking-wide text-[#e6c384]">{{ $categoryName }}</p>
                                            <span class="inline-flex rounded-full border border-[#3a2b18] bg-black/40 px-2 py-0.5 text-[10px] text-gray-300">
                                                {{ $rows->count() }} item
                                            </span>
                                        </div>
                                        <div class="space-y-1">
                                            @foreach ($rows as $row)
                                                <p class="text-sm text-[#f4ead8]">
                                                    {{ $row['item']->unit?->name ?? 'Unit' }}
                                                    <span class="text-xs text-gray-400">({{ $row['item']->unit?->code ?? '-' }})</span>
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">Tidak ada unit</p>
                                @endforelse
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
                        <td class="px-4 py-3 no-print">
                            @if ($loan->status === 'borrowed')
                                @php
                                    $req = isset($returnRequests) ? ($returnRequests[$loan->id] ?? null) : null;
                                    $reqLocationName = '—';
                                    if ($req && !empty($req->return_location_id) && isset($locations)) {
                                        $locObj = $locations->firstWhere('id', $req->return_location_id);
                                        $reqLocationName = $locObj?->name ?? ('#' . $req->return_location_id);
                                    }
                                @endphp

                                @if ($req)
                                    <div class="mb-3 rounded-lg border border-emerald-500/20 bg-emerald-950/10 px-3 py-2">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-emerald-200 mb-2">
                                            Request Pengembalian dari User
                                        </p>
                                        <div class="space-y-1 text-xs text-gray-200">
                                            <p><span class="text-gray-400">Waktu:</span> {{ $req->created_at?->format('d M Y H:i') }}</p>
                                            <p><span class="text-gray-400">Lokasi titip:</span> {{ $reqLocationName }}</p>
                                            <p><span class="text-gray-400">Kondisi:</span> {{ $req->condition ?? '—' }}</p>
                                            <p><span class="text-gray-400">Ulasan:</span> {{ $req->review ?? '—' }}</p>
                                            <p class="break-words"><span class="text-gray-400">Pesan:</span> {{ $req->message ?? '—' }}</p>
                                        </div>
                                    </div>
                                @endif

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
                                                <option value="{{ $loc->id }}" {{ ($req && (string) $req->return_location_id === (string) $loc->id) ? 'selected' : '' }}>
                                                    {{ $loc->name }}
                                                </option>
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
                                                value="{{ $req->condition ?? '' }}"
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
                                                value="{{ $req->review ?? '' }}"
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
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('body_end')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
                color: #000 !important;
            }

            .luxury-nav-wrap,
            .luxury-bg-shape {
                display: none !important;
            }

            .luxury-hero,
            .luxury-card {
                background: #fff !important;
                box-shadow: none !important;
                border: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            table,
            th,
            td {
                color: #000 !important;
                border-color: #ccc !important;
            }
        }
    </style>
@endpush

