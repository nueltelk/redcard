@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Inventaris Barang</h1>
            <p class="text-gray-400">Lihat daftar barang yang tersedia untuk dipinjam.</p>
        </div>
        <a href="/dashboard" class="text-sm font-semibold text-[#e6c384] hover:text-[#f0d9a6] hover:underline underline-offset-4">
            Kembali ke Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/25 bg-emerald-950/25 px-4 py-3 text-sm text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/35 bg-red-950/40 px-4 py-3 text-sm leading-snug text-red-100/95">
            <p class="font-semibold mb-1">Periksa input peminjaman:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-6 bg-black/30 border border-[#d2a14a]/20 rounded-2xl">
        <form method="GET" action="/units" class="mb-5">
            <div class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau kode barang..."
                    class="flex-1 rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
                <button
                    type="submit"
                    class="rounded-xl border border-[#c9a255]/50 bg-[#d2a14a]/15 px-4 py-3 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25"
                >
                    Cari
                </button>
                @if (!empty($search))
                    <a
                        href="/units"
                        class="rounded-xl border border-gray-500/30 bg-white/5 px-4 py-3 text-center text-xs font-bold uppercase tracking-wide text-gray-300 transition hover:bg-white/10"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="mb-4 flex items-center justify-between text-sm">
            <p class="text-gray-400">
                Menampilkan <span class="font-semibold text-[#e6c384]">{{ $units->count() }}</span> barang
                @if (!empty($search))
                    untuk pencarian <span class="font-semibold text-gray-200">"{{ $search }}"</span>
                @endif
            </p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
            <table class="w-full table-fixed text-sm">
                <thead class="bg-[#0d0a07]/70">
                    <tr class="text-left text-gray-300">
                        <th class="w-[24%] px-4 py-3 font-semibold">Nama</th>
                        <th class="w-[12%] px-4 py-3 font-semibold">Kode</th>
                        <th class="w-[18%] px-4 py-3 font-semibold">Stock</th>
                        <th class="w-[30%] px-4 py-3 font-semibold">Deskripsi</th>
                        <th class="w-[16%] px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                    @forelse ($units as $unit)
                        <tr class="align-top hover:bg-white/[0.03] transition">
                            <td class="px-4 py-3 text-[#f4ead8] font-medium">{{ $unit->name }}</td>
                            <td class="px-4 py-3 text-gray-200">
                                <span class="inline-flex rounded-md border border-[#3a2b18] bg-black/30 px-2 py-1 text-xs tracking-wide">
                                    {{ $unit->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-200">
                                @php $stock = (int) ($unit->stock ?? 0); @endphp
                                @if ($stock <= 0)
                                    <span class="inline-flex items-center rounded-full border border-red-500/25 bg-red-500/10 px-3 py-1 text-xs font-bold text-red-200">
                                        Habis (0)
                                    </span>
                                @elseif ($stock <= 3)
                                    <span class="inline-flex items-center rounded-full border border-amber-500/25 bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-200">
                                        Menipis ({{ $stock }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-200">
                                        Tersedia ({{ $stock }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-300 whitespace-normal break-words">{{ $unit->description ?: '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($stock > 0)
                                    <button
                                        type="button"
                                        data-borrow-open
                                        data-unit-id="{{ $unit->id }}"
                                        data-unit-name="{{ $unit->name }}"
                                        class="w-full rounded-lg border border-[#c9a255]/45 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25"
                                    >
                                        Pinjam
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        disabled
                                        class="w-full cursor-not-allowed rounded-lg border border-gray-500/20 bg-white/5 px-3 py-2 text-xs font-bold uppercase tracking-wide text-gray-500"
                                    >
                                        Stok Habis
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                Tidak ada data inventaris.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="borrow-modal" class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/65 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-lg rounded-2xl border border-[#4a3d28] bg-gradient-to-b from-[#231a12] to-[#16110c] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.55)]">
        <div class="mb-5 flex items-start justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-[#e6c384] font-bold mb-2">Form Peminjaman</p>
                <h3 class="text-xl font-semibold text-[#f4ead8]" id="borrow-modal-title">Pinjam Barang</h3>
            </div>
            <button
                type="button"
                id="borrow-modal-close"
                class="rounded-lg border border-[#3a2b18] bg-black/30 px-2.5 py-1.5 text-sm text-gray-200 hover:bg-black/50"
            >
                ✕
            </button>
        </div>

        <form method="POST" action="/borrow" class="space-y-4">
            @csrf
            <input type="hidden" name="units[]" id="borrow-unit-id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="loan_date">Tanggal Pinjam</label>
                    <input
                        id="loan_date"
                        name="loan_date"
                        type="date"
                        value="{{ old('loan_date', now()->format('Y-m-d')) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="pickup_location_id">Lokasi Ambil</label>
                    <select
                        id="pickup_location_id"
                        name="pickup_location_id"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                        <option value="" disabled {{ old('pickup_location_id') ? '' : 'selected' }}>Pilih lokasi</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->id }}" {{ (string) old('pickup_location_id') === (string) $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="start_time">Jam Mulai</label>
                    <input
                        id="start_time"
                        name="start_time"
                        type="time"
                        value="{{ old('start_time', '08:00') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="end_time">Jam Selesai</label>
                    <input
                        id="end_time"
                        name="end_time"
                        type="time"
                        value="{{ old('end_time', '10:00') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="w-full rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3 text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] transition hover:brightness-[1.04]"
            >
                Konfirmasi Pinjam
            </button>
        </form>
    </div>
</div>
@endsection

@push('body_end')
    <script>
        (function () {
            var modal = document.getElementById('borrow-modal');
            var closeBtn = document.getElementById('borrow-modal-close');
            var title = document.getElementById('borrow-modal-title');
            var unitInput = document.getElementById('borrow-unit-id');
            var openButtons = document.querySelectorAll('[data-borrow-open]');

            function openModal(button) {
                if (!modal || !button || !unitInput) return;
                unitInput.value = button.getAttribute('data-unit-id') || '';
                var unitName = button.getAttribute('data-unit-name') || 'Barang';
                if (title) title.textContent = 'Pinjam ' + unitName;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openButtons.forEach(function (btn) {
                btn.addEventListener('click', function () { openModal(btn); });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeModal();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });
        })();
    </script>
@endpush

