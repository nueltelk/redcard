@extends('layouts.luxury')

@section('content')
<div class="flex w-full flex-col items-stretch">
    <header class="mb-9 text-center">
        <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.22em] text-[#e6c384]">Administrator</p>
        <h1 class="mb-2 font-serif text-[1.65rem] font-semibold leading-tight tracking-tight text-[#f4ead8] sm:text-[1.85rem]">
            Panel admin
        </h1>
        <p class="mx-auto max-w-[32ch] text-sm leading-relaxed text-[#8f8068]">
            Username dan kata sandi yang diberikan kepada petugas sistem.
        </p>
    </header>

    @if ($errors->any())
        <div
            class="mb-6 rounded-xl border border-red-500/35 bg-red-950/40 px-4 py-3 text-sm leading-snug text-red-100/95"
            role="alert"
        >
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}" class="flex flex-col gap-0">
        @csrf

        <div class="mb-5">
            <label for="username" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">
                Username
            </label>
            <input
                type="text"
                id="username"
                name="username"
                required
                autocomplete="username"
                value="{{ old('username') }}"
                class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
            >
        </div>

        <div class="mb-2">
            <label for="password" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[#a8987c]">
                Kata sandi
            </label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
            >
        </div>

        <button
            type="submit"
            class="mt-8 w-full cursor-pointer rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3.5 text-center text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] shadow-[0_10px_28px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(255,255,255,0.35)] transition hover:brightness-[1.04] active:translate-y-px"
        >
            Masuk
        </button>
    </form>
</div>
@endsection
