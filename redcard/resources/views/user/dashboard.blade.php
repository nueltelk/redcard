@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-6xl p-8 bg-transparent">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Selamat datang, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-400">Dashboard inventaris dan peminjaman barang sekolah</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-6 bg-gradient-to-br from-[#d2a14a]/10 to-[#d2a14a]/5 border border-[#d2a14a]/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Total Barang Tersedia</p>
            <p class="text-3xl font-bold text-[#d2a14a]">{{ $totalStock ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Unit barang di sistem</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-[#e6c384]/10 to-[#e6c384]/5 border border-[#e6c384]/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Barang Saya Pinjam</p>
            <p class="text-3xl font-bold text-[#e6c384]">{{ $borrowedCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Sedang dipinjam</p>
        </div>
        <div class="p-6 bg-gradient-to-br from-gray-400/10 to-gray-400/5 border border-gray-400/20 rounded-lg">
            <p class="text-sm text-gray-400 mb-2">Riwayat Peminjaman</p>
            <p class="text-3xl font-bold text-gray-300">{{ $historyCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-2">Total peminjaman</p>
        </div>
    </div>

    <div class="mb-8 rounded-xl border border-amber-500/25 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
        Pengembalian unit dilakukan oleh admin. Hubungi admin saat Anda ingin mengembalikan unit.
    </div>

    @error('contact_admin')
        <div class="mb-4 rounded-xl border border-red-500/35 bg-red-950/40 px-4 py-3 text-sm text-red-100">
            {{ $message }}
        </div>
    @enderror

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-6">
        <div class="p-6 bg-black/30 border border-[#d2a14a]/20 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="/units" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📦 Lihat Inventaris
                </a>
                <a href="/history" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    📋 Riwayat Peminjaman Saya
                </a>
                <a href="/profile" class="block p-3 bg-[#d2a14a]/10 hover:bg-[#d2a14a]/20 rounded text-[#d2a14a] transition">
                    👤 Profil Saya
                </a>
                <form method="POST" action="/contact-admin/return">
                    @csrf
                    <input type="hidden" name="message" value="Halo admin, saya ingin mengembalikan barang pinjaman saya.">
                    <input type="hidden" name="redirect_whatsapp" value="1">
                    <button
                        type="submit"
                        class="block w-full rounded p-3 text-left text-[#22c55e] transition bg-[#22c55e]/10 hover:bg-[#22c55e]/20"
                    >
                        💬 Hubungi Admin via WhatsApp (Pengembalian)
                    </button>
                </form>

                @if (!empty($showReturnForm))
                    <div class="mt-2 rounded-xl border border-[#3a2b18] bg-black/20 p-4">
                        <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.12em] text-gray-200">
                            Form Pengembalian Barang
                        </p>

                        @if (($activeLoans ?? collect())->count() === 0)
                            <p class="text-sm text-gray-200">
                                Tidak ada peminjaman aktif.
                            </p>
                        @else
                            <form method="POST" action="/contact-admin/return" class="space-y-3">
                                @csrf
                                <input type="hidden" name="redirect_whatsapp" value="0">

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-200 mb-2">
                                        Pilih Peminjaman
                                    </label>
                                    <select
                                        name="loan_id"
                                        required
                                        class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-gray-100 outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                    >
                                        <option value="" selected disabled class="text-gray-300 bg-black">Pilih pinjaman</option>
                                        @foreach ($activeLoans as $loan)
                                            <option value="{{ $loan->id }}" class="text-gray-100 bg-black">
                                                #{{ $loan->id }} — {{ optional($loan->loan_date)->format('d M Y') }} (Ambil: {{ $loan->pickupLocation?->name ?? '—' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-200 mb-2">
                                        Lokasi Titip Barang (boleh beda dari lokasi ambil)
                                    </label>
                                    <select
                                        name="return_location_id"
                                        required
                                        class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-gray-100 outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                    >
                                        <option value="" selected disabled class="text-gray-300 bg-black">Pilih lokasi</option>
                                        @foreach (($locations ?? []) as $loc)
                                            <option value="{{ $loc->id }}" class="text-gray-100 bg-black">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-200 mb-2">
                                            Kondisi Barang (opsional)
                                        </label>
                                        <input
                                            name="condition"
                                            type="text"
                                            placeholder="Baik / Rusak ringan / ..."
                                            class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-gray-100 outline-none placeholder:text-gray-400 focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-200 mb-2">
                                            Ulasan Penggunaan (opsional)
                                        </label>
                                        <textarea
                                            name="review"
                                            rows="2"
                                            placeholder="Ceritakan pengalaman penggunaan barang..."
                                            class="block w-full resize-none rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-gray-100 outline-none placeholder:text-gray-400 focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-200 mb-2">
                                            Pesan ke Admin (opsional)
                                        </label>
                                        <input
                                            name="message"
                                            type="text"
                                            placeholder="Tambahkan pesan singkat..."
                                            class="block w-full rounded-lg border border-[#3a2b18] bg-black/40 px-3 py-2 text-sm text-gray-100 outline-none placeholder:text-gray-400 focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/20"
                                        >
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full rounded-lg border border-[#c9a255]/40 bg-[#d2a14a]/15 px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#e6c384] hover:bg-[#d2a14a]/25 transition"
                                >
                                    Konfirmasi
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
