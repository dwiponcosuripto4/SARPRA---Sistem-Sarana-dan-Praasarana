<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-[#f8fafc] text-[#131b2e] antialiased">
        @php
            $pengguna = auth()->user();
            $namaPeran = optional($pengguna?->role)->name ?? 'Pengguna';
            $menuAktif = $menuAktif ?? 'dashboard';
        @endphp

        {{-- Sidebar --}}
        <aside class="fixed left-0 top-0 h-full w-64 bg-[#1e3a5f] z-50 hidden lg:flex flex-col justify-between shadow-sm">
            <div class="flex flex-col">
                <div class="h-16 px-6 flex items-center gap-3 border-b border-[#022448]">
                    <x-sarpra-logo class="size-8 rounded-lg shadow-sm" />
                    <div class="flex flex-col">
                        <span class="text-sm font-bold tracking-tight text-white leading-tight">SARPRA</span>
                        <span class="text-xs font-semibold text-[#8aa4cf] tracking-wider uppercase">Asset &amp; Infra</span>
                    </div>
                </div>
                <div class="px-5 pt-6 pb-2">
                    <span class="text-xs font-semibold text-[#8aa4cf] uppercase tracking-wider px-2">Modul Operasional</span>
                </div>
                <nav class="flex flex-col gap-1 px-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ $menuAktif === 'dashboard' ? 'bg-[#022448] text-white font-bold shadow-sm' : 'text-[#8aa4cf] hover:bg-[#254670] hover:text-white' }}">
                        <span class="material-symbols-outlined text-xl">grid_view</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-[#8aa4cf] hover:bg-[#254670] hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-xl">manage_accounts</span>
                        <span>Manajemen Pengguna</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-[#8aa4cf] hover:bg-[#254670] hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-xl">inventory_2</span>
                        <span>Data Master</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-[#8aa4cf] hover:bg-[#254670] hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-xl">settings</span>
                        <span>Konfigurasi Sistem</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-[#8aa4cf] hover:bg-[#254670] hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-xl">history</span>
                        <span>Riwayat Aktivitas</span>
                    </a>
                </nav>
            </div>
            <div class="p-5">
                <div class="mx-1 mb-5 rounded-lg bg-[#022448] p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#85f8c4] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#85f8c4]"></span>
                        </span>
                        <span class="text-xs font-semibold text-white">SARPRA v1.0</span>
                    </div>
                    <div class="text-xs text-[#8aa4cf] leading-relaxed">Sistem Pengelolaan Sarana dan Prasarana</div>
                </div>
            </div>
        </aside>

        <div class="lg:pl-64">
            {{-- Header --}}
            <header class="sticky top-0 z-40 h-16 bg-white flex items-center justify-between px-4 md:px-8 shadow-sm">
                <div class="flex items-center gap-3">
                    <x-sarpra-logo class="size-8 rounded-lg lg:hidden" />
                    <div class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-[#515f74]">
                        <span class="font-bold text-[#1e3a5f]">SARPRA</span>
                        <span>/</span>
                        <span class="text-[#43474e]">{{ $namaPeran }}</span>
                        <span>/</span>
                        <span class="text-[#022448] font-bold bg-[#d5e3fc]/60 px-2 py-0.5 rounded">{{ $judulHalaman ?? 'Dashboard' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 md:gap-5">
                    <div class="flex items-center gap-1">
                        <button aria-label="Notifikasi" class="relative p-2 text-[#515f74] hover:text-[#131b2e] hover:bg-[#f2f3ff] rounded-lg transition-colors" type="button">
                            <span class="material-symbols-outlined text-[22px]">notifications</span>
                            @if (($notifBelumDibaca ?? 0) > 0)
                                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-[#ba1a1a] text-white text-[10px] font-bold">{{ $notifBelumDibaca > 9 ? '9+' : $notifBelumDibaca }}</span>
                            @endif
                        </button>
                        <button aria-label="Bantuan" class="p-2 text-[#515f74] hover:text-[#131b2e] hover:bg-[#f2f3ff] rounded-lg transition-colors" type="button">
                            <span class="material-symbols-outlined text-[22px]">help_outline</span>
                        </button>
                    </div>
                    <div class="h-6 w-px bg-[#e2e7ff] hidden sm:block"></div>
                    <flux:dropdown position="bottom" align="end">
                        <button class="flex items-center gap-2.5 rounded-lg p-1 hover:bg-[#f2f3ff] transition-colors" type="button">
                            <span class="size-9 rounded-full bg-[#1e3a5f] text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $pengguna?->initials() ?? '–' }}</span>
                            <span class="hidden sm:flex flex-col text-left leading-tight">
                                <span class="text-sm font-semibold text-[#131b2e]">{{ $pengguna?->name ?? '–' }}</span>
                                <span class="mt-0.5 self-start px-1.5 py-0.5 rounded bg-[#d5e3fc] text-[#57657a] text-[11px] font-semibold leading-none">{{ $namaPeran }}</span>
                            </span>
                            <span class="material-symbols-outlined text-lg text-[#515f74]">keyboard_arrow_down</span>
                        </button>

                        <flux:menu class="mt-2">
                            <flux:menu.radio.group>
                                <div class="p-0 text-sm font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                        <span class="size-9 rounded-full bg-[#1e3a5f] text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $pengguna?->initials() ?? '–' }}</span>
                                        <div class="grid flex-1 text-start text-sm leading-tight">
                                            <span class="truncate font-semibold text-[#131b2e]">{{ $pengguna?->name ?? '–' }}</span>
                                            <span class="truncate text-xs text-[#515f74]">{{ $pengguna?->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <flux:menu.radio.group>
                                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Pengaturan') }}</flux:menu.item>
                            </flux:menu.radio.group>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item
                                    as="button"
                                    type="submit"
                                    icon="arrow-right-start-on-rectangle"
                                    class="w-full cursor-pointer"
                                    data-test="logout-button"
                                >
                                    {{ __('Keluar') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </header>

            <main class="px-4 md:px-8 py-6 md:py-8 min-h-[calc(100vh-4rem)]">
                {{ $slot }}
            </main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts

        <script>
            // Halaman SARPRA selalu terang: paksa appearance Flux ke light
            // agar menu/dropdown tidak mengikuti dark-mode OS.
            if (window.Flux) {
                window.Flux.applyAppearance('light');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </body>
</html>
