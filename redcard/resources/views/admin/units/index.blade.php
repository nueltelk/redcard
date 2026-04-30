@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Kelola Unit Barang</h1>
            <p class="text-gray-400">Tambah, ubah, dan hapus unit inventaris.</p>
        </div>
        <a href="/admin" class="text-sm font-semibold text-[#e6c384] hover:text-[#f0d9a6] hover:underline underline-offset-4">
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
            <p class="font-semibold mb-1">Periksa input:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 p-6 bg-black/30 border border-[#d2a14a]/20 rounded-2xl">
            <h2 class="text-lg font-semibold text-white mb-4">Tambah Unit</h2>

            <form method="POST" action="/admin/units" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="name">Nama</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="Contoh: Laptop ASUS"
                    >
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="code">Kode</label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        value="{{ old('code') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="Contoh: LP001"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="stock">Jumlah Stock</label>
                    <input
                        id="stock"
                        name="stock"
                        type="number"
                        min="0"
                        value="{{ old('stock', 0) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="0"
                    >
                </div>


                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="description">Deskripsi (opsional)</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="Catatan singkat unit..."
                    >{{ old('description') }}</textarea>
                </div>

                
                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3 text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] shadow-[0_10px_28px_rgba(0,0,0,0.35),inset_0_1px_0_rgba(255,255,255,0.35)] transition hover:brightness-[1.04] active:translate-y-px"
                >
                    Simpan
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 p-6 bg-black/30 border border-[#d2a14a]/10 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Daftar Unit</h2>
                <p class="text-sm text-gray-400">Total: {{ $units->count() }}</p>
            </div>

            <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
                <table class="min-w-full text-sm">
                    <thead class="bg-[#0d0a07]/70">
                        <tr class="text-left text-gray-300">
                            <th class="px-4 py-3 font-semibold">Nama</th>
                            <th class="px-4 py-3 font-semibold">Kode</th>
                            <th class="px-4 py-3 font-semibold">Stock</th>
                            <th class="px-4 py-3 font-semibold">Deskripsi</th>
                            <th class="px-4 py-3 font-semibold w-[140px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                        @forelse ($units as $unit)
                            <tr class="align-top" data-unit-row="{{ $unit->id }}">
                                <td class="px-4 py-3 text-[#f4ead8]">
                                        <input
                                            form="unit-form-{{ $unit->id }}"
                                            name="name"
                                            value="{{ $unit->name }}"
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20 js-edit disabled:opacity-70 disabled:cursor-not-allowed"
                                            disabled
                                        >
                                </td>
                                <td class="px-4 py-3 text-gray-200">
                                        <input
                                            form="unit-form-{{ $unit->id }}"
                                            name="code"
                                            value="{{ $unit->code }}"
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20 js-edit disabled:opacity-70 disabled:cursor-not-allowed"
                                            disabled
                                        >
                                </td>
                                <td class="px-4 py-3 text-gray-200">
                                        <input
                                            form="unit-form-{{ $unit->id }}"
                                            name="stock"
                                            type="number"
                                            min="0"
                                            value="{{ $unit->stock ?? 0 }}"
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20 js-edit disabled:opacity-70 disabled:cursor-not-allowed"
                                            disabled
                                        >
                                </td>
                                <td class="px-4 py-3 text-gray-300">
                                        <textarea
                                            form="unit-form-{{ $unit->id }}"
                                            name="description"
                                            rows="2"
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20 js-edit disabled:opacity-70 disabled:cursor-not-allowed"
                                            disabled
                                        >{{ $unit->description }}</textarea>
                                </td>
                                <td class="px-4 py-3">
                                        <form
                                            id="unit-form-{{ $unit->id }}"
                                            method="POST"
                                            action="/admin/units/{{ $unit->id }}"
                                            class="js-unit-form"
                                            data-unit-form
                                        >
                                            @csrf
                                            @method('PUT')
                                        </form>

                                        <div class="flex flex-col gap-2">
                                            <button
                                                type="button"
                                                data-unit-edit
                                                class="rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] hover:bg-[#d2a14a]/25 transition"
                                            >
                                                Edit
                                            </button>
                                            <div class="hidden gap-2 js-actions-edit">
                                                <button
                                                    type="submit"
                                                    form="unit-form-{{ $unit->id }}"
                                                    class="flex-1 rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/20 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] hover:bg-[#d2a14a]/28 transition"
                                                >
                                                    Simpan
                                                </button>
                                                <button
                                                    type="button"
                                                    data-unit-cancel
                                                    class="flex-1 rounded-lg border border-gray-400/20 bg-white/5 px-3 py-2 text-xs font-bold uppercase tracking-wide text-gray-200 hover:bg-white/10 transition"
                                                >
                                                    Batal
                                                </button>
                                            </div>

                                    <form method="POST" action="/admin/units/{{ $unit->id }}" onsubmit="return confirm('Hapus unit ini?')" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="w-full rounded-lg border border-red-500/25 bg-red-500/10 px-3 py-2 text-xs font-bold uppercase tracking-wide text-red-200 hover:bg-red-500/15 transition"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                        </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                    Belum ada unit. Tambahkan unit pertama di panel kiri.
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

@push('body_end')
    <script>
        (function () {
            var rows = document.querySelectorAll('[data-unit-row]');

            function setEditing(row, isEditing) {
                var unitId = row.getAttribute('data-unit-row');
                var editEls = row.querySelectorAll('[form="unit-form-' + unitId + '"].js-edit');
                var editBtn = row.querySelector('[data-unit-edit]');
                var actions = row.querySelector('.js-actions-edit');

                editEls.forEach(function (el) {
                    el.disabled = !isEditing;
                });
                if (editBtn) editBtn.classList.toggle('hidden', isEditing);
                if (actions) actions.classList.toggle('hidden', !isEditing);

                if (isEditing) {
                    var firstInput = row.querySelector('[form="unit-form-' + unitId + '"].js-edit');
                    if (firstInput) firstInput.focus();
                }
            }

            rows.forEach(function (row) {
                var unitId = row.getAttribute('data-unit-row');
                var editBtn = row.querySelector('[data-unit-edit]');
                var cancelBtn = row.querySelector('[data-unit-cancel]');

                var nameInput = row.querySelector('[form="unit-form-' + unitId + '"][name="name"]');
                var codeInput = row.querySelector('[form="unit-form-' + unitId + '"][name="code"]');
                var descInput = row.querySelector('[form="unit-form-' + unitId + '"][name="description"]');
                var stockInput = row.querySelector('[form="unit-form-' + unitId + '"][name="stock"]');

                row.dataset.origName = nameInput ? nameInput.value : '';
                row.dataset.origCode = codeInput ? codeInput.value : '';
                row.dataset.origDesc = descInput ? descInput.value : '';
                row.dataset.origStock = stockInput ? stockInput.value : '';

                if (editBtn) editBtn.addEventListener('click', function () { setEditing(row, true); });
                if (cancelBtn) cancelBtn.addEventListener('click', function () {
                    if (nameInput) nameInput.value = row.dataset.origName || '';
                    if (codeInput) codeInput.value = row.dataset.origCode || '';
                    if (descInput) descInput.value = row.dataset.origDesc || '';
                    if (stockInput) stockInput.value = row.dataset.origStock || '0';
                    setEditing(row, false);
                });
            });
        })();
    </script>
@endpush

