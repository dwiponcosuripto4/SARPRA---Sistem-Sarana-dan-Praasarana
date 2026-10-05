<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

        @vite(['resources/css/app.css'])

        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-[#faf8ff] text-[#131b2e] antialiased flex flex-col justify-between relative">
        <div class="pointer-events-none fixed inset-0 z-0" style="background: radial-gradient(ellipse 80% 80% at 50% -20%, rgba(30,58,95,0.06), rgba(250,248,255,0));"></div>
        <div class="pointer-events-none fixed inset-0 z-0 opacity-40" style="background-image: linear-gradient(to right, #eaedff 1px, transparent 1px), linear-gradient(to bottom, #eaedff 1px, transparent 1px); background-size: 4rem 4rem;"></div>

        <header class="relative z-10 w-full">
            <div class="max-w-5xl mx-auto flex items-center justify-between px-4 md:px-8 py-5">
                <span class="flex items-center gap-2.5">
                    <x-sarpra-logo class="size-9 rounded-lg shadow-sm" />
                    <span class="text-sm font-bold tracking-tight">SARPRA</span>
                </span>
                @if (Route::has('login'))
                    <nav class="flex items-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-[#515f74] hover:text-[#022448] transition-colors">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="relative z-10 flex-1 flex items-center justify-center w-full px-4 md:px-8 py-10">
            <div class="w-full max-w-3xl flex flex-col items-center">
                <div class="w-full bg-white rounded-xl shadow-xl p-6 md:p-10 relative overflow-hidden text-center">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#022448] to-[#1e3a5f]"></div>

                    <div class="flex justify-center mb-4">
                        <x-sarpra-logo class="size-16 rounded-2xl shadow-sm" />
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-[#d5e3fc]/60 rounded-full mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#1e3a5f]"></span>
                        <span class="text-xs font-semibold tracking-wider uppercase text-[#57657a]">Sistem Pengelolaan Sarana &amp; Prasarana</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Selamat Datang di SARPRA</h1>
                    <p class="text-sm md:text-base text-[#515f74] mt-2 max-w-xl mx-auto">
                        Kelola sarana dan prasarana secara terintegrasi — mulai dari perencanaan
                        kebutuhan, pengadaan, inventarisasi, pemeliharaan, hingga pelaporan.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition-all duration-200 group">
                                <span>Buka Dashboard</span>
                                <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition-all duration-200 group">
                                <span>Masuk ke Sistem</span>
                                <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-[#e2e7ff] hover:bg-[#f2f3ff] text-[#1e3a5f] text-sm font-medium rounded-lg shadow-sm transition-all duration-200">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="w-full grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-6">
                    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-sm p-4 text-left">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-2xl">assignment</span>
                        <p class="text-sm font-semibold mt-2">Perencanaan Kebutuhan</p>
                        <p class="text-xs text-[#515f74] mt-1">Catat dan kelola kebutuhan sarana-prasarana per periode dan prioritas.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-sm p-4 text-left">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-2xl">shopping_cart</span>
                        <p class="text-sm font-semibold mt-2">Pengadaan</p>
                        <p class="text-xs text-[#515f74] mt-1">Pengajuan, persetujuan pimpinan, dan riwayat status pengadaan.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-sm p-4 text-left">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-2xl">inventory_2</span>
                        <p class="text-sm font-semibold mt-2">Inventarisasi</p>
                        <p class="text-xs text-[#515f74] mt-1">Data aset terpusat: kode unik, kondisi, lokasi, dan riwayat barang.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-sm p-4 text-left">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-2xl">build</span>
                        <p class="text-sm font-semibold mt-2">Pemeliharaan</p>
                        <p class="text-xs text-[#515f74] mt-1">Pengajuan servis, pemantauan status, dan dokumentasi perbaikan.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-sm p-4 text-left">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-2xl">bar_chart</span>
                        <p class="text-sm font-semibold mt-2">Pelaporan</p>
                        <p class="text-xs text-[#515f74] mt-1">Laporan inventaris, pengadaan, dan pemeliharaan siap cetak/export.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-sm p-4 text-left">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-2xl">verified_user</span>
                        <p class="text-sm font-semibold mt-2">Akses Berperan</p>
                        <p class="text-xs text-[#515f74] mt-1">Administrator, Pengelola, dan Pimpinan dengan hak akses masing-masing.</p>
                    </div>
                </div>

                <p class="text-xs text-[#515f74]/80 mt-6">© 2026 SARPRA — Sistem Pengelolaan Sarana dan Prasarana</p>
            </div>
        </main>
    </body>
</html>
