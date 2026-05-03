@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Kelola Unit Barang</h1>
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
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="category_id_create">Kategori</label>
                    <select
                        id="category_id_create"
                        name="category_id"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                        <option value="">Pilih satu kategori yang sudah ada</option>
                        @foreach (($categories ?? collect()) as $category)
                            <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button
                        type="button"
                        id="add-category-open"
                        class="mt-2 w-full rounded-xl border border-[#c9a255]/35 bg-[#d2a14a]/10 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/20"
                    >
                        Tambah kategori baru
                    </button>
                    <p id="category-save-hint" class="mt-2 hidden text-xs text-emerald-400/95" role="status"></p>
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
                                                data-unit-category-id="{{ $unit->categories->first()->id ?? '' }}"
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
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="edit_category_id">Kategori</label>
                <select id="edit_category_id" name="category_id" required class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25">
                    <option value="">Pilih satu kategori yang sudah ada</option>
                    @foreach (($categories ?? collect()) as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
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

<div id="add-category-modal" class="fixed inset-0 z-[95] hidden items-center justify-center bg-black/65 p-4 backdrop-blur-[2px]">
    <div class="w-full max-w-md rounded-2xl border border-[#4a3d28] bg-gradient-to-b from-[#231a12] to-[#16110c] p-6 shadow-[0_24px_60px_rgba(0,0,0,0.55)]">
        <div class="mb-4 flex items-start justify-between gap-3">
            <div>
                
                <h3 class="mt-1 text-lg font-semibold text-[#f4ead8]">Tambah kategori baru</h3>
            </div>
            <button
                type="button"
                id="add-category-modal-close"
                class="rounded-lg border border-[#3a2b18] bg-black/30 px-2.5 py-1.5 text-sm text-gray-200 hover:bg-black/50"
            >
                ✕
            </button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="new_category_name">Nama kategori</label>
                <input
                    id="new_category_name"
                    type="text"
                    autocomplete="off"
                    placeholder="Contoh: Laptop"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
                <p id="add-category-error" class="mt-2 hidden text-xs text-red-300/95"></p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button
                    type="button"
                    id="add-category-submit"
                    class="rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25 disabled:opacity-50"
                >
                    Simpan
                </button>
                <button
                    type="button"
                    id="add-category-cancel"
                    class="rounded-lg border border-gray-400/20 bg-white/5 px-3 py-2 text-xs font-bold uppercase tracking-wide text-gray-200 transition hover:bg-white/10"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('body_end')
    <script>
        (function () {
            var csrfToken = @json(csrf_token());

            var catModal = document.getElementById('add-category-modal');
            var catOpenBtn = document.getElementById('add-category-open');
            var catCloseBtn = document.getElementById('add-category-modal-close');
            var catCancelBtn = document.getElementById('add-category-cancel');
            var catSubmitBtn = document.getElementById('add-category-submit');
            var catNameInput = document.getElementById('new_category_name');
            var catErr = document.getElementById('add-category-error');
            var catHint = document.getElementById('category-save-hint');
            var selectCreate = document.getElementById('category_id_create');
            var selectEdit = document.getElementById('edit_category_id');

            function insertCategorySorted(selectEl, id, label) {
                if (!selectEl) return;
                var opt = document.createElement('option');
                opt.value = String(id);
                opt.textContent = label;
                var options = selectEl.querySelectorAll('option');
                var i = 1;
                while (i < options.length) {
                    if (options[i].textContent.localeCompare(label, 'id', { sensitivity: 'base' }) > 0) {
                        selectEl.insertBefore(opt, options[i]);
                        return;
                    }
                    i++;
                }
                selectEl.appendChild(opt);
            }

            function openCatModal() {
                if (!catModal) return;
                if (catErr) {
                    catErr.classList.add('hidden');
                    catErr.textContent = '';
                }
                if (catNameInput) catNameInput.value = '';
                catModal.classList.remove('hidden');
                catModal.classList.add('flex');
                if (catNameInput) catNameInput.focus();
            }

            function closeCatModal() {
                if (!catModal) return;
                catModal.classList.add('hidden');
                catModal.classList.remove('flex');
            }

            function showHint(text) {
                if (!catHint) return;
                catHint.textContent = text;
                catHint.classList.remove('hidden');
                window.clearTimeout(showHint._t);
                showHint._t = window.setTimeout(function () {
                    catHint.classList.add('hidden');
                }, 4000);
            }

            if (catSubmitBtn) {
                catSubmitBtn.addEventListener('click', function () {
                    var name = catNameInput ? catNameInput.value.trim() : '';
                    if (!name) {
                        if (catErr) {
                            catErr.textContent = 'Isi nama kategori.';
                            catErr.classList.remove('hidden');
                        }
                        return;
                    }
                    if (catErr) catErr.classList.add('hidden');
                    catSubmitBtn.disabled = true;
                    fetch('/admin/categories', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ category_name: name }),
                    })
                        .then(function (res) {
                            return res.json().then(function (data) {
                                return { ok: res.ok, status: res.status, data: data };
                            });
                        })
                        .then(function (result) {
                            catSubmitBtn.disabled = false;
                            if (result.ok && result.data.category) {
                                var c = result.data.category;
                                insertCategorySorted(selectCreate, c.id, c.name);
                                insertCategorySorted(selectEdit, c.id, c.name);
                                if (selectCreate) selectCreate.value = String(c.id);
                                closeCatModal();
                                showHint(result.data.message || 'Kategori tersimpan.');
                                return;
                            }
                            var msg = 'Gagal menyimpan.';
                            if (result.data.errors && result.data.errors.category_name) {
                                msg = result.data.errors.category_name[0];
                            } else if (result.data.message) {
                                msg = result.data.message;
                            }
                            if (catErr) {
                                catErr.textContent = msg;
                                catErr.classList.remove('hidden');
                            }
                        })
                        .catch(function () {
                            catSubmitBtn.disabled = false;
                            if (catErr) {
                                catErr.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
                                catErr.classList.remove('hidden');
                            }
                        });
                });
            }

            if (catOpenBtn) catOpenBtn.addEventListener('click', openCatModal);
            if (catCloseBtn) catCloseBtn.addEventListener('click', closeCatModal);
            if (catCancelBtn) catCancelBtn.addEventListener('click', closeCatModal);
            if (catModal) {
                catModal.addEventListener('click', function (e) {
                    if (e.target === catModal) closeCatModal();
                });
            }
            if (catNameInput) {
                catNameInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (catSubmitBtn) catSubmitBtn.click();
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && catModal && catModal.classList.contains('flex')) closeCatModal();
            });
        })();
    </script>
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
            var categorySelect = document.getElementById('edit_category_id');

            function openModal(button) {
                if (!modal || !form || !button) return;

                var unitId = button.getAttribute('data-unit-id') || '';
                form.action = '/admin/units/' + unitId;

                if (nameInput) nameInput.value = button.getAttribute('data-unit-name') || '';
                if (codeInput) codeInput.value = button.getAttribute('data-unit-code') || '';
                if (stockInput) stockInput.value = button.getAttribute('data-unit-stock') || '0';
                if (descInput) descInput.value = button.getAttribute('data-unit-description') || '';

                if (categorySelect) {
                    categorySelect.value = button.getAttribute('data-unit-category-id') || '';
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
                if (e.key !== 'Escape') return;
                var catM = document.getElementById('add-category-modal');
                if (catM && catM.classList.contains('flex')) return;
                closeModal();
            });
        })();
    </script>
@endpush

