{{-- Topbar header SARPRA: breadcrumb, notifikasi, profil + dropdown. --}}
@php
    $pengguna = auth()->user();
    $namaPeran = $pengguna?->role?->name ?? 'Pengguna';
@endphp

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
