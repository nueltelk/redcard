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

        <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
            <table class="w-full table-fixed text-sm">
                <thead class="bg-[#0d0a07]/70">
                    <tr class="text-gray-300">
                        <th class="w-[20%] px-4 py-3 text-left font-semibold">Kategori</th>
                        <th class="w-[26%] px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="w-[12%] px-4 py-3 text-center font-semibold">Kode</th>
                        <th class="w-[16%] px-4 py-3 text-center font-semibold">Stock</th>
                        <th class="w-[14%] px-4 py-3 text-left font-semibold">Deskripsi</th>
                        <th class="w-[12%] px-4 py-3 text-center font-semibold">Aksi</th>
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
                                <td class="px-4 py-3.5 text-center">
                                    @if ($stock > 0)
                                        <button
                                            type="button"
                                            data-borrow-open
                                            data-unit-id="{{ $unit->id }}"
                                            data-unit-name="{{ $unit->name }}"
                                            class="w-full max-w-[130px] rounded-lg border border-[#c9a255]/45 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] transition hover:bg-[#d2a14a]/25"
                                        >
                                            Pinjam
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            disabled
                                            class="w-full max-w-[130px] cursor-not-allowed rounded-lg border border-gray-500/20 bg-white/5 px-3 py-2 text-xs font-bold uppercase tracking-wide text-gray-500"
                                        >
                                            Stok Habis
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
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

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="loan_date">Tanggal Pinjam</label>
                    <input
                        id="loan_date"
                        name="loan_date"
                        type="text"
                        inputmode="numeric"
                        autocomplete="off"
                        maxlength="10"
                        placeholder="dd/mm/yyyy"
                        pattern="\d{1,2}/\d{1,2}/\d{4}"
                        title="Format: tanggal/bulan/tahun (dd/mm/yyyy)"
                        value="{{ old('loan_date', now()->format('d/m/Y')) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="return_date">Tanggal Pengembalian</label>
                    <input
                        id="return_date"
                        name="return_date"
                        type="text"
                        inputmode="numeric"
                        autocomplete="off"
                        maxlength="10"
                        placeholder="dd/mm/yyyy"
                        pattern="\d{1,2}/\d{1,2}/\d{4}"
                        title="Format: tanggal/bulan/tahun (dd/mm/yyyy)"
                        value="{{ old('return_date', now()->addDays(5)->format('d/m/Y')) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                    <p class="mt-1.5 text-[11px] text-[#8a7d6a]">Isi tanggal dalam format <span class="text-[#a8987c]">hari/bulan/tahun (dd/mm/yyyy)</span>. Otomatis terisi pinjam + 5 hari; Anda boleh ubah lebih awal (mis. 2–4 hari).</p>
                    <p id="return-date-warning" class="mt-2 hidden rounded-lg border border-red-500/35 bg-red-950/40 px-3 py-2 text-xs text-red-100">
                        Maksimal peminjaman 5 hari dari tanggal pinjam.
                    </p>
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
                            <option
                                value="{{ $loc->id }}"
                                data-lat="{{ $loc->latitude }}"
                                data-lng="{{ $loc->longitude }}"
                                {{ (string) old('pickup_location_id') === (string) $loc->id ? 'selected' : '' }}
                            >
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="rounded-xl border border-[#3a2b18] bg-[#0d0a07]/55 p-4">
                <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Preview Lokasi</p>
                <p id="pickup-coordinate" class="mb-3 text-xs text-gray-300">Pilih lokasi untuk melihat koordinat.</p>
                <div class="overflow-hidden rounded-lg border border-[#3a2b18]">
                    <iframe
                        id="pickup-map-frame"
                        title="Peta lokasi pengambilan"
                        class="h-80 w-full"
                        src=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
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
                id="borrow-submit"
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
            var pickupSelect = document.getElementById('pickup_location_id');
            var mapFrame = document.getElementById('pickup-map-frame');
            var coordinateLabel = document.getElementById('pickup-coordinate');
            var loanDateInput = document.getElementById('loan_date');
            var returnDateInput = document.getElementById('return_date');
            var returnDateWarning = document.getElementById('return-date-warning');
            var submitBtn = document.getElementById('borrow-submit');

            function openModal(button) {
                if (!modal || !button || !unitInput) return;
                unitInput.value = button.getAttribute('data-unit-id') || '';
                var unitName = button.getAttribute('data-unit-name') || 'Barang';
                if (title) title.textContent = 'Pinjam ' + unitName;
                renderPickupMap();
                validateReturnDateRange();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function buildOpenStreetMapUrl(lat, lng) {
                var delta = 0.005;
                var left = (lng - delta).toFixed(6);
                var bottom = (lat - delta).toFixed(6);
                var right = (lng + delta).toFixed(6);
                var top = (lat + delta).toFixed(6);

                return 'https://www.openstreetmap.org/export/embed.html?bbox='
                    + left + '%2C' + bottom + '%2C' + right + '%2C' + top
                    + '&layer=mapnik&marker=' + lat.toFixed(6) + '%2C' + lng.toFixed(6);
            }

            function renderPickupMap() {
                if (!pickupSelect || !mapFrame || !coordinateLabel) return;

                var selected = pickupSelect.options[pickupSelect.selectedIndex];
                if (!selected || !selected.value) {
                    coordinateLabel.textContent = 'Pilih lokasi untuk melihat koordinat.';
                    mapFrame.src = '';
                    return;
                }

                var lat = parseFloat(selected.getAttribute('data-lat') || '');
                var lng = parseFloat(selected.getAttribute('data-lng') || '');

                if (!isFinite(lat) || !isFinite(lng)) {
                    coordinateLabel.textContent = 'Koordinat lokasi belum tersedia.';
                    mapFrame.src = '';
                    return;
                }

                coordinateLabel.textContent = 'Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
                mapFrame.src = buildOpenStreetMapUrl(lat, lng);
            }

            /** Tanggal dianggap lengkap (boleh diparse & di-jepit): dd/mm/yyyy atau yyyy-mm-dd. */
            function isCompleteCalendarDateString(str) {
                if (!str) return false;
                var s = String(str).trim();
                if (/^\d{4}-\d{2}-\d{2}$/.test(s)) return true;
                return /^\d{1,2}\/\d{1,2}\/\d{4}$/.test(s);
            }

            /**
             * Parse teks tanggal: dd/mm/yyyy (Indonesia) atau cadangan yyyy-mm-dd.
             * Untuk format slash: tahun harus 4 digit — supaya "15/05/2" tidak dianggap tahun 2 M (lalu di-jepit ke tanggal pinjam).
             */
            function parseYmdFromInput(inputEl) {
                if (!inputEl) return null;
                var value = inputEl.value;
                if (!value) return null;
                var str = String(value).trim();

                var dash = str.split('-');
                if (dash.length === 3) {
                    if (!/^\d{4}-\d{2}-\d{2}$/.test(str)) return null;
                    var y = parseInt(dash[0], 10);
                    var m = parseInt(dash[1], 10);
                    var d = parseInt(dash[2], 10);
                    if (isFinite(y) && isFinite(m) && isFinite(d) && m >= 1 && m <= 12 && d >= 1 && d <= 31) {
                        return { y: y, m: m, d: d };
                    }
                }

                var slash = str.split('/');
                if (slash.length === 3) {
                    var yPart = String(slash[2]).trim();
                    if (!/^\d{4}$/.test(yPart)) return null;

                    var a = parseInt(slash[0], 10);
                    var b = parseInt(slash[1], 10);
                    var y2 = parseInt(yPart, 10);
                    if (!isFinite(y2) || !isFinite(a) || !isFinite(b)) return null;

                    var day;
                    var month;
                    if (a > 12) {
                        day = a; month = b;
                    } else if (b > 12) {
                        month = a; day = b;
                    } else {
                        day = a; month = b;
                    }

                    if (month >= 1 && month <= 12 && day >= 1 && day <= 31) {
                        return { y: y2, m: month, d: day };
                    }
                }

                return null;
            }

            function ymdToLocalDate(ymd) {
                if (!ymd) return null;
                var d = new Date(ymd.y, ymd.m - 1, ymd.d);
                return isFinite(d.getTime()) ? d : null;
            }

            function localDateFromInput(inputEl) {
                return ymdToLocalDate(parseYmdFromInput(inputEl));
            }

            /** Tampilan & nilai form: hari/bulan/tahun (dd/mm/yyyy). */
            function formatDmyLocal(d) {
                if (!(d instanceof Date) || !isFinite(d.getTime())) return '';
                var day = String(d.getDate()).padStart(2, '0');
                var m = String(d.getMonth() + 1).padStart(2, '0');
                var y = d.getFullYear();
                return day + '/' + m + '/' + y;
            }

            function addCalendarDaysLocal(date, days) {
                var d = new Date(date.getFullYear(), date.getMonth(), date.getDate() + days);
                return isFinite(d.getTime()) ? d : null;
            }

            /** Selisih hari kalender antara dua tanggal lokal (inklusif sama hari = 0). */
            function calendarDaysBetween(start, end) {
                var ms = end.getTime() - start.getTime();
                return Math.round(ms / 86400000);
            }

            /** Set tanggal kembali = tanggal pinjam + 5 hari (nilai yang disarankan / batas atas). Dipanggil saat tanggal pinjam diset lewat picker. */
            function applyDefaultReturnFiveDaysAhead() {
                if (!loanDateInput || !returnDateInput) return;
                var loanDate = localDateFromInput(loanDateInput);
                if (!loanDate) return;
                var suggested = addCalendarDaysLocal(loanDate, 5);
                if (!suggested) return;
                returnDateInput.value = formatDmyLocal(suggested);
                validateReturnDateRange();
            }

            /**
             * Durasi pinjam mengikuti backend (maks. loan_date + 5 hari kalender).
             * opts: { clampReturn?: boolean } — false pada event input tanggal kembali (jangan jepit saat tahun belum 4 digit).
             */
            function validateReturnDateRange(opts) {
                opts = opts || {};
                var shouldClampReturn = opts.clampReturn !== false;

                if (!loanDateInput || !returnDateInput) return true;

                if (!loanDateInput.value) {
                    if (returnDateWarning) returnDateWarning.classList.add('hidden');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                    return true;
                }

                var loanDate = localDateFromInput(loanDateInput);
                if (!loanDate) {
                    if (returnDateWarning) returnDateWarning.classList.add('hidden');
                    if (submitBtn) {
                        var loanNonEmpty = String(loanDateInput.value || '').trim().length > 0;
                        submitBtn.disabled = loanNonEmpty;
                        submitBtn.classList.toggle('opacity-50', loanNonEmpty);
                        submitBtn.classList.toggle('cursor-not-allowed', loanNonEmpty);
                    }
                    return false;
                }

                var maxReturnDate = addCalendarDaysLocal(loanDate, 5);
                if (!maxReturnDate) return true;

                var retRaw = String(returnDateInput.value || '').trim();
                var returnComplete = isCompleteCalendarDateString(retRaw);
                var returnDate = returnComplete ? localDateFromInput(returnDateInput) : null;

                if (shouldClampReturn && returnComplete && returnDate) {
                    var clamped = returnDate;
                    if (clamped < loanDate) clamped = loanDate;
                    if (clamped > maxReturnDate) clamped = maxReturnDate;
                    var clampedStr = formatDmyLocal(clamped);
                    if (returnDateInput.value.trim() !== clampedStr) {
                        returnDateInput.value = clampedStr;
                        returnDate = clamped;
                    }
                }

                var span = returnComplete && returnDate ? calendarDaysBetween(loanDate, returnDate) : NaN;
                var rangeInvalid = returnComplete && returnDate && (span < 0 || span > 5);

                if (!returnComplete) {
                    if (returnDateWarning) returnDateWarning.classList.add('hidden');
                } else if (returnDateWarning) {
                    returnDateWarning.classList.toggle('hidden', !rangeInvalid);
                }

                var blockSubmit = false;
                if (retRaw.length > 0 && !returnComplete) {
                    blockSubmit = true;
                } else if (returnComplete && !returnDate) {
                    blockSubmit = true;
                } else if (rangeInvalid) {
                    blockSubmit = true;
                }
                if (submitBtn) {
                    submitBtn.disabled = blockSubmit;
                    submitBtn.classList.toggle('opacity-50', blockSubmit);
                    submitBtn.classList.toggle('cursor-not-allowed', blockSubmit);
                }

                return !blockSubmit;
            }

            openButtons.forEach(function (btn) {
                btn.addEventListener('click', function () { openModal(btn); });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (pickupSelect) pickupSelect.addEventListener('change', renderPickupMap);
            if (loanDateInput) loanDateInput.addEventListener('change', applyDefaultReturnFiveDaysAhead);
            if (returnDateInput) {
                returnDateInput.addEventListener('change', function () {
                    validateReturnDateRange({ clampReturn: true });
                });
                returnDateInput.addEventListener('blur', function () {
                    validateReturnDateRange({ clampReturn: true });
                });
                returnDateInput.addEventListener('input', function () {
                    validateReturnDateRange({ clampReturn: false });
                });
            }
            if (loanDateInput) loanDateInput.addEventListener('input', validateReturnDateRange);
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeModal();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });

            var borrowForm = modal ? modal.querySelector('form[action="/borrow"]') : null;
            if (borrowForm) {
                borrowForm.addEventListener('submit', function (e) {
                    if (!validateReturnDateRange({ clampReturn: true })) {
                        e.preventDefault();
                    }
                });
            }

            renderPickupMap();
            validateReturnDateRange();
        })();
    </script>
@endpush

