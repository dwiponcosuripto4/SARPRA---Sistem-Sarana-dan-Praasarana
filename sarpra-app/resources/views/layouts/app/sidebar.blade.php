{{-- Sidebar SARPRA: menu modul operasional per role. --}}
@php
    $namaPeran = auth()->user()?->role?->name;
    $menuAktif = $menuAktif ?? 'dashboard';

    $menuAdmin = [
        ['key' => 'dashboard', 'icon' => 'grid_view', 'label' => 'Dashboard', 'href' => route('dashboard')],
        ['key' => 'pengguna', 'icon' => 'manage_accounts', 'label' => 'Manajemen Pengguna', 'href' => route('pengguna.index')],
        ['key' => 'master', 'icon' => 'inventory_2', 'label' => 'Data Master', 'href' => route('master.index')],
        ['key' => 'aktivitas', 'icon' => 'history', 'label' => 'Riwayat Aktivitas', 'href' => route('aktivitas.index')],
    ];

    $menuPengelola = [
        ['key' => 'dashboard', 'icon' => 'grid_view', 'label' => 'Dashboard', 'href' => route('dashboard')],
        ['key' => 'perencanaan', 'icon' => 'assignment', 'label' => 'Perencanaan Kebutuhan', 'href' => route('perencanaan.index')],
        ['key' => 'pengadaan', 'icon' => 'shopping_bag', 'label' => 'Pengadaan', 'href' => route('pengadaan.index')],
        ['key' => 'inventarisasi', 'icon' => 'inventory_2', 'label' => 'Inventarisasi', 'href' => route('inventarisasi.index')],
        ['key' => 'pemeliharaan', 'icon' => 'build_circle', 'label' => 'Pemeliharaan', 'href' => route('pemeliharaan.index')],
    ];

    $menuPimpinan = [
        ['key' => 'dashboard', 'icon' => 'grid_view', 'label' => 'Dashboard', 'href' => route('dashboard')],
        ['key' => 'persetujuan', 'icon' => 'assignment_turned_in', 'label' => 'Pengadaan & Persetujuan', 'href' => route('persetujuan.index')],
        ['key' => 'monitoring', 'icon' => 'monitoring', 'label' => 'Monitoring Aset', 'href' => route('monitoring.index')],
        ['key' => 'pelaporan', 'icon' => 'summarize', 'label' => 'Pelaporan', 'href' => route('pelaporan.index')],
    ];

    $menuItems = match ($namaPeran) {
        'Pengelola Sarana & Prasarana' => $menuPengelola,
        'Pimpinan' => $menuPimpinan,
        default => $menuAdmin,
    };
@endphp

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
            @foreach ($menuItems as $item)
                <a href="{{ $item['href'] }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ $menuAktif === $item['key'] ? 'bg-[#022448] text-white font-bold shadow-sm' : 'text-[#8aa4cf] hover:bg-[#254670] hover:text-white' }}">
                    <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
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
