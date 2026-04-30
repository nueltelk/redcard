@extends('layouts.luxury')

@section('content')
<div class="flex w-full flex-col items-stretch">
    <header class="mb-9 text-center">
        <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.22em] text-[#e6c384]">RedCard</p>
        <h1 class="mb-2 font-serif text-[1.65rem] font-semibold leading-tight tracking-tight text-[#f4ead8] sm:text-[1.85rem]">
            Masuk
        </h1>
        <p class="mx-auto max-w-[32ch] text-sm leading-relaxed text-[#8f8068]">
            Username dan kata sandi akun Anda.
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

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-0">
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
                value="{{ old('username', session('login_username')) }}"
                placeholder="masukkan username"
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
                placeholder="********"
                class="block w-full rounded-xl border border-[#3a2b18] bg-[#0d0a07]/90 px-4 py-3.5 text-[0.9375rem] text-[#f4ead8] shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] outline-none transition placeholder:text-[#5c5244] focus:border-[#b8893d] focus:ring-2 focus:ring-[#d2a14a]/25"
            >
        </div>

        <p class="mt-7 text-center text-sm leading-relaxed text-[#8f8068]">
            Belum punya akun?
            <a
                href="{{ route('register') }}"
                class="font-semibold text-[#e6c384] underline-offset-4 hover:text-[#f0d9a6] hover:underline"
            >
                Daftar di sini
            </a>
        </p>

        <button
            type="submit"
            class="mt-5 w-full cursor-pointer rounded-xl border border-[#c9a255]/50 bg-gradient-to-b from-[#f0d28a] via-[#d2a14a] to-[#b8893d] py-3.5 text-center text-xs font-extrabold uppercase tracking-[0.16em] text-[#1a1208] shadow-[0_10px_28px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(255,255,255,0.35)] transition hover:brightness-[1.04] active:translate-y-px"
        >
            Lanjutkan
        </button>
    </form>
</div>
@endsection

@if (session('registered'))
    @push('body_end')
        <div
            id="registration-success-modal"
            class="registration-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="registration-success-title"
        >
            <div class="registration-modal__card">
                <div class="registration-modal__icon" aria-hidden="true">?</div>
                <h2 id="registration-success-title" class="registration-modal__title">Registrasi berhasil</h2>
                <p class="registration-modal__text">
                    Selamat, kamu berhasil registrasi. Silakan masuk dengan username dan kata sandi yang baru saja kamu buat.
                </p>
                <button type="button" id="registration-success-close" class="registration-modal__button">
                    Mengerti
                </button>
            </div>
        </div>
        <script>
            (function () {
                var modal = document.getElementById('registration-success-modal');
                var btn = document.getElementById('registration-success-close');

                function closeModal() {
                    if (modal) {
                        modal.remove();
                    }
                }

                if (btn) {
                    btn.addEventListener('click', closeModal);
                }

                if (modal) {
                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) {
                            closeModal();
                        }
                    });
                }

                function onEsc(e) {
                    if (e.key === 'Escape') {
                        closeModal();
                        document.removeEventListener('keydown', onEsc);
                    }
                }

                document.addEventListener('keydown', onEsc);
            })();
        </script>
    @endpush
@endif
