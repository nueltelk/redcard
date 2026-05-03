@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Kelola Lokasi Pengambilan</h1>

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
            <p class="font-semibold mb-1">Periksa input lokasi:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 p-6 bg-black/30 border border-[#d2a14a]/20 rounded-2xl">
            <h2 class="text-lg font-semibold text-white mb-4">Tambah Lokasi</h2>

            <form method="POST" action="/admin/locations" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="name">Nama Lokasi</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="Contoh: TULT"
                    >
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="latitude">Latitude</label>
                    <input
                        id="latitude"
                        name="latitude"
                        type="number"
                        step="0.0000001"
                        value="{{ old('latitude') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="-6.9692337"
                    >
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c] mb-2" for="longitude">Longitude</label>
                    <input
                        id="longitude"
                        name="longitude"
                        type="number"
                        step="0.0000001"
                        value="{{ old('longitude') }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-[0.9375rem] text-[#f4ead8] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                        placeholder="107.6281458"
                    >
                </div>

                <div class="rounded-xl border border-[#3a2b18] bg-[#0d0a07]/55 p-3">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Preview Peta</p>
                    <p id="new-location-coordinates" class="mb-2 text-xs text-gray-300">Isi latitude dan longitude untuk melihat peta.</p>
                    <div class="overflow-hidden rounded-lg border border-[#3a2b18]">
                        <iframe
                            id="new-location-map"
                            title="Preview peta lokasi baru"
                            class="h-56 w-full"
                            src=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3 text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] shadow-[0_10px_28px_rgba(0,0,0,0.35),inset_0_1px_0_rgba(255,255,255,0.35)] transition hover:brightness-[1.04] active:translate-y-px"
                >
                    Simpan Lokasi
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 p-6 bg-black/30 border border-[#d2a14a]/10 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Daftar Lokasi</h2>
                <p class="text-sm text-gray-400">Total: {{ $locations->count() }}</p>
            </div>

            <div class="overflow-x-auto rounded-xl border border-[#3a2b18]">
                <table class="min-w-full text-sm">
                    <thead class="bg-[#0d0a07]/70">
                        <tr class="text-left text-gray-300">
                            <th class="px-4 py-3 font-semibold">Nama Lokasi</th>
                            <th class="px-4 py-3 font-semibold">Latitude</th>
                            <th class="px-4 py-3 font-semibold">Longitude</th>
                            <th class="px-4 py-3 font-semibold">Peta</th>
                            <th class="px-4 py-3 font-semibold w-[200px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#3a2b18] bg-[#0d0a07]/30">
                        @forelse ($locations as $location)
                            <tr class="align-top">
                                <td class="px-4 py-3">
                                    <form method="POST" action="/admin/locations/{{ $location->id }}" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        <input
                                            name="name"
                                            value="{{ $location->name }}"
                                            required
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        >
                                </td>
                                <td class="px-4 py-3">
                                        <input
                                            name="latitude"
                                            type="number"
                                            step="0.0000001"
                                            value="{{ $location->latitude }}"
                                            required
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        >
                                </td>
                                <td class="px-4 py-3">
                                        <input
                                            name="longitude"
                                            type="number"
                                            step="0.0000001"
                                            value="{{ $location->longitude }}"
                                            required
                                            class="w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        >
                                </td>
                                <td class="px-4 py-3">
                                    <div class="overflow-hidden rounded-lg border border-[#3a2b18]">
                                        <iframe
                                            title="Peta {{ $location->name }}"
                                            class="h-44 w-64"
                                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $location->longitude - 0.005 }}%2C{{ $location->latitude - 0.005 }}%2C{{ $location->longitude + 0.005 }}%2C{{ $location->latitude + 0.005 }}&layer=mapnik&marker={{ $location->latitude }}%2C{{ $location->longitude }}"
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                        ></iframe>
                                    </div>
                                    <p class="mt-1 text-[11px] text-gray-400">
                                        {{ number_format((float) $location->latitude, 6, '.', '') }},
                                        {{ number_format((float) $location->longitude, 6, '.', '') }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                        <button
                                            type="submit"
                                            class="w-full rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] hover:bg-[#d2a14a]/25 transition"
                                        >
                                            Update
                                        </button>
                                    </form>

                                    <form method="POST" action="/admin/locations/{{ $location->id }}" class="mt-2" onsubmit="return confirm('Hapus lokasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="w-full rounded-lg border border-red-500/25 bg-red-500/10 px-3 py-2 text-xs font-bold uppercase tracking-wide text-red-200 hover:bg-red-500/15 transition"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    Belum ada lokasi. Tambahkan lokasi pertama.
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
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');
            var mapFrame = document.getElementById('new-location-map');
            var coordinateText = document.getElementById('new-location-coordinates');

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

            function renderNewLocationMap() {
                if (!latInput || !lngInput || !mapFrame || !coordinateText) return;

                var lat = parseFloat(latInput.value || '');
                var lng = parseFloat(lngInput.value || '');

                if (!isFinite(lat) || !isFinite(lng)) {
                    coordinateText.textContent = 'Isi latitude dan longitude untuk melihat peta.';
                    mapFrame.src = '';
                    return;
                }

                coordinateText.textContent = 'Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
                mapFrame.src = buildOpenStreetMapUrl(lat, lng);
            }

            if (latInput) latInput.addEventListener('input', renderNewLocationMap);
            if (lngInput) lngInput.addEventListener('input', renderNewLocationMap);

            renderNewLocationMap();
        })();
    </script>
@endpush
