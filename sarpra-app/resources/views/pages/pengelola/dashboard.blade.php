<x-layouts::app :title="__('Dashboard Pengelola')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="dashboard">
    @php
        $pctBaik = $totalUnit > 0 ? round($baik / $totalUnit * 100, 1) : 0;
        $kondisiBadge = [
            'Baik' => ['kelas' => 'bg-[#ecfdf5] text-[#047857]', 'dot' => 'bg-[#059669]'],
            'Rusak Ringan' => ['kelas' => 'bg-[#fffbeb] text-[#b45309]', 'dot' => 'bg-[#d97706]'],
            'Rusak Berat' => ['kelas' => 'bg-[#fef2f2] text-[#b91c1c]', 'dot' => 'bg-[#dc2626]'],
        ];
    @endphp

    {{-- Sapaan --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div class="flex flex-col gap-1">
            <div class="inline-flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[11px] font-bold text-[#515f74] uppercase tracking-wider">Konsol Operasional Pengelola</span>
            </div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-[#475569]">Ringkasan inventarisasi, kondisi sarana prasarana, dan pengajuan operasional aktif hari ini</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="inline-flex items-center bg-[#e2e7ff] border border-[#e2e7ff] rounded-lg p-1 text-xs" id="time-filter-group">
                <button type="button" class="filter-btn px-3 py-1.5 rounded-md font-bold bg-white text-[#022448] shadow-xs transition-colors">Hari Ini</button>
                <button type="button" class="filter-btn px-3 py-1.5 rounded-md font-medium text-[#515f74] hover:text-[#022448] transition-colors">7 Hari</button>
                <button type="button" class="filter-btn px-3 py-1.5 rounded-md font-medium text-[#515f74] hover:text-[#022448] transition-colors">30 Hari</button>
            </div>
            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a5f] hover:bg-[#254670] text-white rounded-lg text-xs font-semibold shadow-sm transition-all">
                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                <span>Buat Pengajuan Pengadaan</span>
            </a>
        </div>
    </div>

    {{-- Kartu metrik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mt-6">
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Total Aset</span>
                <span class="p-2 rounded-lg bg-[#f1f5f9] text-[#022448] material-symbols-outlined text-xl">inventory_2</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-bold text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($totalUnit, 0, ',', '.') }} <span class="text-xs font-sans text-[#515f74] font-medium">Unit</span></div>
                <div class="text-[11px] text-[#475569] mt-0.5">Terdaftar dalam {{ $jumlahKategori }} kategori</div>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Barang Baik</span>
                <span class="p-2 rounded-lg bg-[#ecfdf5] text-[#047857] material-symbols-outlined text-xl">check_circle</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-bold text-[#047857]" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($baik, 0, ',', '.') }} <span class="text-xs font-sans text-[#515f74] font-medium">Unit</span></div>
                <div class="text-[11px] text-[#475569] mt-0.5"><span class="font-semibold text-[#047857]">{{ number_format($pctBaik, 1, ',', '.') }}%</span> kondisi optimal</div>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Rusak Ringan</span>
                <span class="p-2 rounded-lg bg-[#fffbeb] text-[#b45309] material-symbols-outlined text-xl">report_problem</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-bold text-[#b45309]" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($ringan, 0, ',', '.') }} <span class="text-xs font-sans text-[#515f74] font-medium">Unit</span></div>
                <div class="text-[11px] text-[#475569] mt-0.5">Perlu pemeliharaan berkala</div>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Rusak Berat</span>
                <span class="p-2 rounded-lg bg-[#fef2f2] text-[#b91c1c] material-symbols-outlined text-xl">cancel</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-bold text-[#b91c1c]" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($berat, 0, ',', '.') }} <span class="text-xs font-sans text-[#515f74] font-medium">Unit</span></div>
                <div class="text-[11px] text-[#475569] mt-0.5">Usulan penghapusan / lelang</div>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Pengadaan Pending</span>
                <span class="p-2 rounded-lg bg-[#d5e3fc]/60 text-[#022448] material-symbols-outlined text-xl">hourglass_top</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $pending }} <span class="text-xs font-sans text-[#515f74] font-medium">Pengajuan</span></div>
                <div class="text-[11px] text-[#475569] mt-0.5">Menunggu verifikasi</div>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Pemeliharaan Aktif</span>
                <span class="p-2 rounded-lg bg-[#e2e7ff]/40 text-[#515f74] material-symbols-outlined text-xl">engineering</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-bold text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ $tiketAktif }} <span class="text-xs font-sans text-[#515f74] font-medium">Tiket</span></div>
                <div class="text-[11px] text-[#475569] mt-0.5">Sedang dikerjakan teknisi</div>
            </div>
        </div>
    </div>

    {{-- Aksi cepat --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <a href="#" class="p-4 rounded-xl bg-[#1e3a5f] text-white shadow-sm flex items-start gap-4 transition-all hover:bg-[#254670]">
            <div class="p-2.5 rounded-lg bg-white/10 flex-shrink-0">
                <span class="material-symbols-outlined text-2xl">add_shopping_cart</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold tracking-tight">Buat Pengajuan Pengadaan</span>
                <p class="text-xs text-[#bacae3] mt-0.5 leading-relaxed">Ajukan usulan rencana kebutuhan sarpras baru untuk unit kerja</p>
            </div>
        </a>
        <a href="#" class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-start gap-4 transition-all hover:bg-[#f1f5f9] group">
            <div class="p-2.5 rounded-lg bg-[#f1f5f9] text-[#022448] flex-shrink-0 group-hover:bg-[#1e3a5f] group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl">qr_code_scanner</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-[#0f172a] group-hover:text-[#022448] transition-colors">Catat Aset Baru / Inventarisasi</span>
                <p class="text-xs text-[#475569] mt-0.5 leading-relaxed">Pindai QR/Barcode atau input serial number aset baru</p>
            </div>
        </a>
        <a href="#" class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-start gap-4 transition-all hover:bg-[#f1f5f9] group">
            <div class="p-2.5 rounded-lg bg-[#f1f5f9] text-[#022448] flex-shrink-0 group-hover:bg-[#1e3a5f] group-hover:text-white transition-colors">
                <span class="material-symbols-outlined text-2xl">build</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-[#0f172a] group-hover:text-[#022448] transition-colors">Lapor Pemeliharaan / Kerusakan</span>
                <p class="text-xs text-[#475569] mt-0.5 leading-relaxed">Buat tiket servis perbaikan sarana prasarana</p>
            </div>
        </a>
    </div>

    {{-- Tabel aset --}}
    <div class="rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col overflow-hidden mt-4">
        <form method="GET" action="{{ route('dashboard') }}" class="p-5 border-b border-[#e2e7ff] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex flex-col">
                <h2 class="text-sm font-bold text-[#0f172a] tracking-tight">Ringkasan Status Aset Terkini</h2>
                <p class="text-xs text-[#475569] mt-0.5">Daftar inventaris sarpras yang diverifikasi dan dalam pemantauan operasional</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="inline-flex items-center bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg p-0.5 text-xs">
                    @foreach (['' => 'Semua', 'Baik' => 'Kondisi Baik', 'Rusak Ringan' => 'Rusak Ringan', 'Rusak Berat' => 'Rusak Berat'] as $val => $label)
                        <button type="submit" name="kondisi" value="{{ $val }}" class="px-3 py-1 rounded-md {{ request('kondisi', '') === $val ? 'font-semibold bg-white text-[#022448] shadow-xs' : 'text-[#515f74] hover:text-[#022448]' }}">{{ $label }}</button>
                    @endforeach
                </div>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-2.5 text-base text-[#515f74] pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode / nama aset..." class="w-56 h-8 pl-8 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] border-b border-[#e2e7ff] text-[#515f74] font-semibold">
                        <th class="px-4 py-3 font-medium">Kode Inventaris</th>
                        <th class="px-4 py-3 font-medium">Nama Aset &amp; Spesifikasi</th>
                        <th class="px-4 py-3 font-medium">Lokasi / Ruangan</th>
                        <th class="px-4 py-3 font-medium">Kondisi</th>
                        <th class="px-4 py-3 font-medium">Cek Terakhir</th>
                        <th class="px-4 py-3 font-medium">Status Operasional</th>
                        <th class="px-4 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff]">
                    @forelse ($aset as $b)
                        @php $kb = $kondisiBadge[$b->kondisi?->name] ?? ['kelas' => 'bg-[#f1f5f9] text-[#515f74]', 'dot' => 'bg-[#94a3b8]']; @endphp
                        <tr class="hover:bg-[#f1f5f9] transition-colors">
                            <td class="px-4 py-3 font-semibold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $b->kode_barang }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-[#0f172a]">{{ $b->nama_barang }}</div>
                                <div class="text-[11px] text-[#475569]">{{ \Illuminate\Support\Str::limit($b->spesifikasi, 60) }}</div>
                            </td>
                            <td class="px-4 py-3 text-[#475569]">{{ $b->lokasi?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-semibold text-[11px] {{ $kb['kelas'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $kb['dot'] }}"></span>{{ $b->kondisi?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-[#475569]" style="font-family: 'JetBrains Mono', monospace;">{{ $b->updated_at?->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc]/50 text-[#57657a] font-medium text-[11px]">{{ $b->status?->name ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" title="Lihat Detail" class="p-1 text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded"><span class="material-symbols-outlined text-lg">visibility</span></button>
                                    <button type="button" title="Edit Aset" class="p-1 text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded"><span class="material-symbols-outlined text-lg">edit</span></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-4 text-center text-sm text-[#515f74]">Tidak ada data aset untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-[#e2e7ff] flex items-center justify-between text-xs text-[#515f74]">
            <span class="text-[11px]">Menampilkan <strong class="text-[#0f172a] font-semibold">{{ $aset->firstItem() ?? 0 }} - {{ $aset->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a] font-semibold">{{ number_format($aset->total(), 0, ',', '.') }}</strong> aset</span>
            <div class="flex items-center gap-1">
                @if ($aset->onFirstPage())
                    <span class="px-2.5 py-1 border border-[#e2e7ff] rounded bg-[#f1f5f9] text-[11px]">Sebelumnya</span>
                @else
                    <a href="{{ $aset->previousPageUrl() }}" class="px-2.5 py-1 border border-[#e2e7ff] rounded bg-white hover:bg-[#f1f5f9] text-[11px]">Sebelumnya</a>
                @endif
                @foreach ($aset->getUrlRange(max(1, $aset->currentPage() - 1), min($aset->lastPage(), $aset->currentPage() + 1)) as $page => $url)
                    @if ($page == $aset->currentPage())
                        <span class="px-2.5 py-1 border border-[#e2e7ff] rounded bg-[#1e3a5f] text-white font-semibold text-[11px]">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-2.5 py-1 border border-[#e2e7ff] rounded bg-white hover:bg-[#f1f5f9] text-[11px]">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($aset->hasMorePages())
                    <a href="{{ $aset->nextPageUrl() }}" class="px-2.5 py-1 border border-[#e2e7ff] rounded bg-white hover:bg-[#f1f5f9] text-[11px]">Berikutnya</a>
                @else
                    <span class="px-2.5 py-1 border border-[#e2e7ff] rounded bg-[#f1f5f9] text-[11px]">Berikutnya</span>
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
            const filterButtons = document.querySelectorAll('#time-filter-group .filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterButtons.forEach(b => {
                        b.classList.remove('bg-white', 'text-[#022448]', 'shadow-xs', 'font-bold');
                        b.classList.add('text-[#515f74]', 'font-medium');
                    });
                    btn.classList.add('bg-white', 'text-[#022448]', 'shadow-xs', 'font-bold');
                    btn.classList.remove('text-[#515f74]', 'font-medium');
                });
            });
        })();
    </script>
</x-layouts::app>
