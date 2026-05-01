@extends('layouts.luxury')

@section('content')
<div class="mx-auto max-w-3xl p-6 sm:p-8 bg-transparent">
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-[#e6c384]">Profil</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Profil Saya</h1>
            <p class="text-gray-400">Kelola informasi akun Anda secara mandiri.</p>
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
        <form method="POST" action="/profile" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Nama</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
                <div>
                    <label for="username" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Username</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username', $user->username) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
                <div>
                    <label for="phone" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">No. Telepon</label>
                    <input
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone', $profile->phone) }}"
                        class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    >
                </div>
            </div>

            <div>
                <label for="address" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">Alamat</label>
                <textarea
                    id="address"
                    name="address"
                    rows="4"
                    class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3 text-sm text-[#f4ead8] outline-none placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
                    placeholder="Alamat lengkap..."
                >{{ old('address', $profile->address) }}</textarea>
            </div>

            <button
                type="submit"
                class="w-full rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3 text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] transition hover:brightness-[1.04]"
            >
                Simpan Perubahan
            </button>
        </form>
    </div>

</div>
@endsection

