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
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="category_names_create">Kategori (bisa lebih dari satu)</label>
                    <select
                        id="category_names_create"
                        name="category_names[]"
                        multiple
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                        @foreach (($categories ?? collect()) as $category)
                            <option value="{{ $category->name }}" {{ collect(old('category_names', []))->contains($category->name) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Tekan Ctrl (Windows) untuk pilih lebih dari satu.</p>
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

            @php
                $categorizedUnits = collect($units ?? [])
                    ->map(function ($unit) {
                        $categories = collect($unit->categories ?? [])->sortBy('name')->values();
                        $unitName = strtoupper((string) $unit->name);

                        $primaryCategory = $categories->first(function ($category) use ($unitName) {
                            $categoryName = strtoupper((string) ($category->name ?? ''));
                            return $categoryName !== '' && str_contains($unitName, $categoryName);
                        }) ?? $categories->first();

                        return [
                            'category' => strtoupper((string) ($primaryCategory->name ?? 'Tanpa Kategori')),
                            'unit' => $unit,
                        ];
                    })
                    ->groupBy('category')
                    ->sortKeys();
            @endphp

            <div class="mb-6 overflow-x-auto rounded-xl border border-[#3a2b18]">
                <table class="w-full table-fixed text-sm">
                    <thead class="bg-[#0d0a07]/70">
                        <tr class="text-gray-300">
                            <th class="w-[18%] px-4 py-3 text-left font-semibold">Kategori</th>
                            <th class="w-[22%] px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="w-[10%] px-4 py-3 text-center font-semibold">Kode</th>
                            <th class="w-[13%] px-4 py-3 text-center font-semibold">Stock</th>
                            <th class="w-[22%] px-4 py-3 text-left font-semibold">Deskripsi</th>
                            <th class="w-[15%] px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#0d0a07]/30">
                        @forelse ($categorizedUnits as $categoryName => $rows)
                            @foreach ($rows as $index => $row)
                                @php
                                    $unit = $row['unit'];
                                    $stock = (int) ($unit->stock ?? 0);
                                @endphp
                                <tr class="align-middle border-t border-[#3a2b18] hover:bg-white/[0.03] transition">
                                    @if ($index === 0)
                                        <td rowspan="{{ $rows->count() }}" class="px-4 py-4 align-top">
                                            <div class="rounded-xl border border-[#c9a255]/20 bg-[#d2a14a]/5 px-3 py-2">
                                                <p class="text-sm font-bold text-[#e6c384]">{{ $categoryName }}</p>
                                                <span class="mt-2 inline-flex rounded-full border border-[#3a2b18] bg-black/35 px-2 py-0.5 text-xs text-gray-300">
                                                    {{ $rows->count() }} item
                                                </span>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-4 py-3.5 text-[#f4ead8] font-medium">{{ $unit->name }}</td>
                                    <td class="px-4 py-3.5 text-center text-gray-200">
                                        <span class="inline-flex rounded-md border border-[#3a2b18] bg-black/30 px-2 py-1 text-xs tracking-wide">
                                            {{ $unit->code }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center text-gray-200">
                                        @if ($stock <= 0)
                                            <span class="inline-flex items-center rounded-full border border-red-500/25 bg-red-500/10 px-3 py-1 text-xs font-bold text-red-200">
                                                Habis (0)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full border border-emerald-500/25 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-200">
                                                Tersedia ({{ $stock }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-300 whitespace-normal break-words">{{ $unit->description ?: '—' }}</td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex flex-col gap-2">
                                            <button
                                                type="button"
                                                data-unit-edit-open
                                                data-unit-id="{{ $unit->id }}"
                                                data-unit-name="{{ $unit->name }}"
                                                data-unit-code="{{ $unit->code }}"
                                                data-unit-stock="{{ $unit->stock ?? 0 }}"
                                                data-unit-description="{{ $unit->description ?? '' }}"
                                                data-unit-categories='@json($unit->categories->pluck("name")->values())'
                                                class="w-full rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25"
                                            >
                                                Edit
                                            </button>
                                            <form method="POST" action="/admin/units/{{ $unit->id }}" onsubmit="return confirm('Hapus unit ini?')" class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="w-full rounded-lg border border-red-500/25 bg-red-500/10 px-3 py-2 text-xs font-bold uppercase tracking-wide text-red-200 transition hover:bg-red-500/15"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
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

<div id="edit-unit-modal" class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/65 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-xl rounded-2xl border border-[#4a3d28] bg-gradient-to-b from-[#231a12] to-[#16110c] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.55)]">
        <div class="mb-5 flex items-start justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.16em] text-[#e6c384] font-bold mb-2">Kelola Unit</p>
                <h3 class="text-xl font-semibold text-[#f4ead8]">Edit Unit Barang</h3>
            </div>
            <button
                type="button"
                id="edit-unit-modal-close"
                class="rounded-lg border border-[#3a2b18] bg-black/30 px-2.5 py-1.5 text-sm text-gray-200 hover:bg-black/50"
            >
                ✕
            </button>
        </div>

        <form id="edit-unit-form" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="edit_name">Nama</label>
                <input id="edit_name" name="name" type="text" required class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="edit_code">Kode</label>
                    <input id="edit_code" name="code" type="text" required class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="edit_stock">Jumlah Stock</label>
                    <input id="edit_stock" name="stock" type="number" min="0" required class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="edit_category_names">Kategori (bisa lebih dari satu)</label>
                <select id="edit_category_names" name="category_names[]" multiple class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25">
                    @foreach (($categories ?? collect()) as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Tekan Ctrl (Windows) untuk pilih lebih dari satu.</p>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="edit_description">Deskripsi (opsional)</label>
                <textarea id="edit_description" name="description" rows="3" class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button
                    type="submit"
                    class="w-full rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25"
                >
                    Simpan
                </button>
                <button
                    type="button"
                    id="edit-unit-modal-cancel"
                    class="w-full rounded-lg border border-gray-400/20 bg-white/5 px-3 py-2 text-xs font-bold uppercase tracking-wide text-gray-200 transition hover:bg-white/10"
                >
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('body_end')
    <script>
        (function () {
            var modal = document.getElementById('edit-unit-modal');
            var closeBtn = document.getElementById('edit-unit-modal-close');
            var cancelBtn = document.getElementById('edit-unit-modal-cancel');
            var openButtons = document.querySelectorAll('[data-unit-edit-open]');

            var form = document.getElementById('edit-unit-form');
            var nameInput = document.getElementById('edit_name');
            var codeInput = document.getElementById('edit_code');
            var stockInput = document.getElementById('edit_stock');
            var descInput = document.getElementById('edit_description');
            var categorySelect = document.getElementById('edit_category_names');

            function openModal(button) {
                if (!modal || !form || !button) return;

                var unitId = button.getAttribute('data-unit-id') || '';
                form.action = '/admin/units/' + unitId;

                if (nameInput) nameInput.value = button.getAttribute('data-unit-name') || '';
                if (codeInput) codeInput.value = button.getAttribute('data-unit-code') || '';
                if (stockInput) stockInput.value = button.getAttribute('data-unit-stock') || '0';
                if (descInput) descInput.value = button.getAttribute('data-unit-description') || '';

                var selectedCategories = [];
                try {
                    selectedCategories = JSON.parse(button.getAttribute('data-unit-categories') || '[]');
                } catch (e) {
                    selectedCategories = [];
                }

                if (categorySelect) {
                    Array.from(categorySelect.options).forEach(function (opt) {
                        opt.selected = selectedCategories.indexOf(opt.value) !== -1;
                    });
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (nameInput) nameInput.focus();
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(btn);
                });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
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

