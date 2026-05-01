@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-6 sm:p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-[#e6c384]">Riwayat</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Riwayat Peminjaman Saya</h1>
            <p class="text-gray-400">Pantau status peminjaman, tenggat, dan total denda Anda.</p>
        </div>
        <a
            href="/dashboard"
            class="inline-flex items-center gap-2 rounded-xl border border-[#3a2b18] bg-black/30 px-3.5 py-2 text-sm font-semibold text-[#e6c384] transition hover:bg-black/45 hover:text-[#f0d9a6]"
        >
            <span aria-hidden="true">←</span>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <div class="mb-5 rounded-xl border border-amber-500/25 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
        Pengembalian unit hanya dapat diproses oleh admin. Jika ingin mengembalikan unit, silakan hubungi admin.
    </div>

    @error('contact_admin')
        <div class="mb-5 rounded-xl border border-red-500/35 bg-red-950/40 px-4 py-3 text-sm text-red-100">
            {{ $message }}
        </div>
    @enderror

    <div class="rounded-2xl border border-[#d2a14a]/20 bg-black/30 p-5 sm:p-6">
        <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
            <table class="w-full table-fixed text-sm">
                <thead class="bg-[#0d0a07]/70">
                    <tr class="text-center text-gray-300">
                        <th class="w-[14%] px-4 py-3.5 font-semibold">Tanggal Pinjam</th>
                        <th class="w-[14%] px-4 py-3.5 font-semibold">Tanggal Pengembalian</th>
                        <th class="w-[15%] px-4 py-3.5 font-semibold">Lokasi Ambil</th>
                        <th class="w-[20%] px-4 py-3.5 font-semibold">Unit & Kategori</th>
                        <th class="w-[10%] px-4 py-3.5 font-semibold">Jam Mulai</th>
                        <th class="w-[10%] px-4 py-3.5 font-semibold">Jam Selesai</th>
                        <th class="w-[8%] px-4 py-3.5 font-semibold">Status</th>
                        <th class="w-[9%] px-4 py-3.5 font-semibold">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                    @forelse ($loans as $loan)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="px-4 py-3.5 text-center text-[#f4ead8] font-medium whitespace-nowrap">
                                {{ optional($loan->loan_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200 whitespace-nowrap">
                                {{ $loan->return_date ? $loan->return_date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200">
                                {{ $loan->pickupLocation?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-left text-gray-200">
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
                                        <span class="text-xs text-gray-500">Tidak ada unit</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200 whitespace-nowrap">
                                {{ \Illuminate\Support\Str::of((string) $loan->start_time)->substr(0, 5) }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200 whitespace-nowrap">
                                {{ \Illuminate\Support\Str::of((string) $loan->end_time)->substr(0, 5) }}
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                @if ($loan->status === 'borrowed')
                                    <span class="inline-flex items-center rounded-full border border-amber-500/25 bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-200">
                                        Dipinjam
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-200">
                                        Dikembalikan
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200 whitespace-nowrap">
                                @if (($loan->fine ?? 0) > 0)
                                    <span class="inline-flex items-center rounded-full border border-red-500/25 bg-red-500/10 px-3 py-1 text-xs font-bold text-red-200">
                                        Rp {{ number_format($loan->fine ?? 0, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                Belum ada riwayat peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

