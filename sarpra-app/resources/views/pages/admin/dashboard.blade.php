<x-layouts::app :title="__('Dashboard Administrator')" :notifBelumDibaca="$notifBelumDibaca">
    @php
        $labelAktivitas = [
            'login' => 'Autentikasi', 'tambah' => 'Tambah Data', 'ubah' => 'Ubah Data',
            'hapus' => 'Hapus Data', 'ajukan' => 'Pengajuan', 'setujui' => 'Persetujuan',
            'tolak' => 'Penolakan', 'ubah_status' => 'Perubahan Status',
        ];
        $warnaAvatar = ['bg-[#022448]', 'bg-[#1e3a5f]', 'bg-amber-700', 'bg-rose-700', 'bg-emerald-700'];
    @endphp

    {{-- Judul --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Dashboard Administrator</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sistem Aktif
                </span>
            </div>
            <p class="text-sm text-[#515f74]">Ringkasan metrik pengguna, status sistem, dan log aktivitas operasional SARPRA terkini.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
                <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
                <span>{{ $tanggalHariIni }}</span>
            </div>
            <button id="refresh-data-btn" type="button" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors shadow-xs">
                <span id="refresh-icon" class="material-symbols-outlined text-lg transition-transform">refresh</span>
                <span>Segarkan Data</span>
            </button>
        </div>
    </div>

    {{-- Kartu metrik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex items-center justify-between hover:border-[#cbd5e1] transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Total Pengguna Aktif</span>
                <div class="flex items-baseline gap-2 flex-wrap">
                    <span class="text-3xl font-extrabold text-[#0f172a] tracking-tight">{{ $totalAktif }}</span>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">+{{ $baruBulanIni }} dari bulan lalu</span>
                </div>
                <p class="text-xs text-[#515f74] pt-0.5">{{ $aktifMingguIni }} aktif minggu ini</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#d5e3fc]/70 flex items-center justify-center text-[#022448] shadow-xs shrink-0">
                <span class="material-symbols-outlined text-[28px]">group</span>
            </div>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex items-center justify-between hover:border-[#cbd5e1] transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Total Role</span>
                <div class="flex items-baseline gap-2 flex-wrap">
                    <span class="text-3xl font-extrabold text-[#0f172a] tracking-tight">{{ $roles->count() }} Role</span>
                </div>
                <p class="text-xs text-[#515f74] pt-0.5">{{ $roles->pluck('name')->join(', ') }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#d5e3fc]/70 flex items-center justify-center text-[#022448] shadow-xs shrink-0">
                <span class="material-symbols-outlined text-[28px]">verified_user</span>
            </div>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex items-center justify-between hover:border-[#cbd5e1] transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Total Data Master</span>
                <div class="flex items-baseline gap-2 flex-wrap">
                    <span class="text-3xl font-extrabold text-[#0f172a] tracking-tight">{{ $totalMaster }} Data</span>
                </div>
                <p class="text-xs text-[#515f74] pt-0.5">9 entitas • Kategori, Satuan, Lokasi &amp; lainnya</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#d5e3fc]/70 flex items-center justify-center text-[#022448] shadow-xs shrink-0">
                <span class="material-symbols-outlined text-[28px]">dataset</span>
            </div>
        </div>
    </div>

    {{-- Tabel aktivitas --}}
    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('dashboard') }}" class="p-5 border-b border-[#e2e7ff] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-[#0f172a]">Aktivitas Pengguna Terbaru</h2>
                <p class="text-xs text-[#515f74] mt-0.5">Catatan riwayat aktivitas pengguna lintas modul SARPRA</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari pengguna / aksi..." class="w-60 h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f] transition-all">
                </div>
                <div class="relative">
                    <select name="role" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none focus:border-[#1e3a5f] cursor-pointer transition-all appearance-none">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}" @selected(request('role') === $r->name)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
                </div>
                <div class="relative">
                    <select name="aktivitas" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none focus:border-[#1e3a5f] cursor-pointer transition-all appearance-none">
                        <option value="">Semua Aktivitas</option>
                        @foreach ($labelAktivitas as $key => $label)
                            <option value="{{ $key }}" @selected(request('aktivitas') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
                </div>
                <a href="{{ route('dashboard.ekspor', request()->query()) }}" class="h-9 px-3 bg-[#f1f5f9] hover:bg-[#e2e7ff] border border-[#e2e7ff] rounded-lg text-xs font-semibold text-[#515f74] flex items-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-base">file_download</span>
                    <span>Ekspor</span>
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th scope="col" class="py-3.5 px-5">Waktu</th>
                        <th scope="col" class="py-3.5 px-5">Pengguna</th>
                        <th scope="col" class="py-3.5 px-5">Role</th>
                        <th scope="col" class="py-3.5 px-5">Aktivitas</th>
                        <th scope="col" class="py-3.5 px-5">Status</th>
                        <th scope="col" class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($aktivitas as $a)
                        @php $st = $a->statusTampilan(); @endphp
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5 whitespace-nowrap text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $a->created_at?->locale('id')->translatedFormat('d M Y, H:i:s') }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full {{ $warnaAvatar[($a->user_id ?? 0) % count($warnaAvatar)] }} text-white font-bold flex items-center justify-center text-xs shrink-0">{{ $a->user?->initials() ?? '–' }}</div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ $a->user?->name ?? 'Sistem' }}</span>
                                        <span class="text-[11px] text-[#515f74]">{{ $a->user?->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc] text-[#57657a] text-[10px] font-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $a->user?->role?->name ?? 'Sistem' }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $labelAktivitas[$a->activity] ?? $a->activity }}</span>
                                    <span class="text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $a->description }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $st['kelas'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span> {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-right">
                                <button class="text-[#515f74] hover:text-[#022448] p-1 rounded hover:bg-[#e2e7ff] transition-colors" title="Detail" type="button">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data aktivitas untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#f1f5f9]/40">
            <div class="flex items-center gap-3 text-xs text-[#515f74]">
                <span>Menampilkan <strong class="text-[#0f172a] font-semibold">{{ $aktivitas->firstItem() ?? 0 }} - {{ $aktivitas->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a] font-semibold">{{ $aktivitas->total() }}</strong> data aktivitas</span>
                <span class="text-[#94a3b8]">|</span>
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-1.5">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <span>Baris per halaman:</span>
                    <select name="per_halaman" onchange="this.form.submit()" class="h-7 px-2 bg-white border border-[#e2e7ff] rounded text-xs text-[#0f172a] focus:outline-none">
                        @foreach ([6, 10, 25, 50] as $n)
                            <option value="{{ $n }}" @selected($aktivitas->perPage() == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-1">
                @if ($aktivitas->onFirstPage())
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#94a3b8] bg-[#f1f5f9] flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">chevron_left</span>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $aktivitas->previousPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] hover:bg-[#f1f5f9] flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-base">chevron_left</span>
                        <span>Sebelumnya</span>
                    </a>
                @endif
                @foreach ($aktivitas->getUrlRange(max(1, min($aktivitas->currentPage() - 1, $aktivitas->lastPage() - 2)), min($aktivitas->lastPage(), max(3, $aktivitas->currentPage() + 1))) as $page => $url)
                    @if ($page == $aktivitas->currentPage())
                        <span class="w-8 h-8 rounded-lg bg-[#022448] text-white text-xs font-semibold flex items-center justify-center shadow-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg border border-[#e2e7ff] text-[#515f74] hover:bg-[#f1f5f9] text-xs font-medium flex items-center justify-center transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($aktivitas->lastPage() > 3 && $aktivitas->currentPage() < $aktivitas->lastPage() - 1)
                    <span class="px-1.5 text-xs text-[#515f74]">...</span>
                    <a href="{{ $aktivitas->url($aktivitas->lastPage()) }}" class="w-8 h-8 rounded-lg border border-[#e2e7ff] text-[#515f74] hover:bg-[#f1f5f9] text-xs font-medium flex items-center justify-center transition-colors">{{ $aktivitas->lastPage() }}</a>
                @endif
                @if ($aktivitas->hasMorePages())
                    <a href="{{ $aktivitas->nextPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] hover:bg-[#f1f5f9] flex items-center gap-1 transition-colors">
                        <span>Selanjutnya</span>
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </a>
                @else
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#94a3b8] bg-[#f1f5f9] flex items-center gap-1">
                        <span>Selanjutnya</span>
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <footer class="pt-5 pb-2 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-[#515f74] text-xs mt-6">
        <div class="flex items-center gap-2">
            <span class="font-bold text-[#022448]">SARPRA</span>
            <span>•</span>
            <span>Sistem Terpadu Pengelolaan Sarana dan Prasarana</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Operasional Aktif</span>
            <span>•</span>
            <span>Hak Cipta © 2026</span>
        </div>
    </footer>

    <script>
        (function() {
            const refreshBtn = document.getElementById('refresh-data-btn');
            const refreshIcon = document.getElementById('refresh-icon');
            if (refreshBtn && refreshIcon) {
                refreshBtn.addEventListener('click', () => {
                    refreshIcon.classList.add('rotate-180');
                    refreshBtn.classList.add('opacity-75');
                    setTimeout(() => window.location.reload(), 500);
                });
            }
        })();
    </script>
</x-layouts::app>
