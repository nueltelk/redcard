@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-3xl p-6 sm:p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-[#e6c384]">Profil</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Profil Saya</h1>
            <p class="text-gray-400">Sama seperti data saat pendaftaran: nama, username, email, dan ubah kata sandi (opsional).</p>
        </div>
        <a
            href="/dashboard"
            class="text-sm font-semibold text-[#e6c384] hover:text-[#f0d9a6] hover:underline underline-offset-4"
        >
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

    <div class="rounded-2xl border border-[#d2a14a]/20 bg-black/30 p-5 sm:p-6">
        <form method="POST" action="/profile" class="flex flex-col gap-0">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="name" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Nama lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value="{{ old('name', $user->name) }}"
                    autocomplete="name"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
            </div>

            <div class="mb-5">
                <label for="username" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    value="{{ old('username', $user->username) }}"
                    autocomplete="username"
                    placeholder="contoh: budi_001"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
            </div>

            <div class="mb-5">
                <label for="email" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="{{ old('email', $user->email) }}"
                    autocomplete="email"
                    placeholder="nama@institusi.com"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
            </div>

            <p class="mb-3 mt-2 text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Ubah kata sandi <span class="font-normal normal-case text-[#6b6154]">(opsional, kosongkan jika tidak diganti)</span></p>

            <div class="mb-5">
                <label for="password" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Kata sandi baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Minimal 6 karakter"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
            </div>

            <div class="mb-2">
                <label for="password_confirmation" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Konfirmasi kata sandi</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi baru"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                >
            </div>

            <button
                type="submit"
                class="mt-8 w-full cursor-pointer rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3.5 text-center text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] shadow-[0_10px_28px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(255,255,255,0.35)] transition hover:brightness-[1.04] active:translate-y-px"
            >
                Simpan Perubahan
            </button>
        </form>
    </div>

</div>
@endsection
