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

    <div class="rounded-2xl border border-[#d2a14a]/20 bg-black/30 p-5 sm:p-6">
        <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
            <table class="w-full table-fixed text-sm">
                <thead class="bg-[#0d0a07]/70">
                    <tr class="text-center text-gray-300">
                        <th class="w-[20%] px-4 py-3.5 font-semibold">Tanggal Peminjaman</th>
                        <th class="w-[24%] px-4 py-3.5 font-semibold">Lokasi Ambil</th>
                        <th class="w-[18%] px-4 py-3.5 font-semibold">Jam Mulai</th>
                        <th class="w-[18%] px-4 py-3.5 font-semibold">Jam Selesai</th>
                        <th class="w-[20%] px-4 py-3.5 font-semibold">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                    @forelse ($loans as $loan)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="px-4 py-3.5 text-center text-[#f4ead8] font-medium whitespace-nowrap">
                                {{ optional($loan->loan_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200">
                                {{ $loan->pickupLocation?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200 whitespace-nowrap">
                                {{ \Illuminate\Support\Str::of((string) $loan->start_time)->substr(0, 5) }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-gray-200 whitespace-nowrap">
                                {{ \Illuminate\Support\Str::of((string) $loan->end_time)->substr(0, 5) }}
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
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
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

