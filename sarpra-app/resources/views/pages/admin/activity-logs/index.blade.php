<x-layouts::app :title="__('Riwayat Aktivitas Sistem')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="aktivitas" judulHalaman="Aktivitas">
    @php
        $labelAktivitas = [
            'login' => 'Login', 'tambah' => 'Tambah', 'ubah' => 'Ubah', 'hapus' => 'Hapus',
            'ajukan' => 'Ajukan', 'setujui' => 'Setujui', 'tolak' => 'Tolak', 'ubah_status' => 'Ubah Status',
        ];
        $badgeAksi = [
            'login' => 'bg-blue-100 text-blue-800',
            'tambah' => 'bg-emerald-100 text-emerald-800',
            'ubah' => 'bg-amber-100 text-amber-800',
            'hapus' => 'bg-red-100 text-red-700',
            'ajukan' => 'bg-indigo-100 text-indigo-700',
            'setujui' => 'bg-emerald-100 text-emerald-800',
            'tolak' => 'bg-red-100 text-red-700',
            'ubah_status' => 'bg-slate-200 text-slate-700',
        ];
        $labelModul = [
            'pengguna' => 'Manajemen Pengguna', 'perencanaan' => 'Modul Perencanaan',
            'pengadaan' => 'Modul Pengadaan', 'barang' => 'Modul Inventaris',
            'pemeliharaan' => 'Modul Pemeliharaan', 'laporan' => 'Laporan',
        ];
    @endphp

    <style>
        @media print {
            aside, header, form, footer, .no-print { display: none !important; }
            main { padding: 0 !important; }
        }
    </style>

    {{-- Judul --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-[#e2e7ff]">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded bg-[#1e3a5f]/10 text-[#1e3a5f] text-xs font-bold tracking-wide uppercase">Konsol Administrator</span>
                <span class="text-xs text-[#94a3b8]">•</span>
                <span class="text-xs text-[#515f74] font-medium">Pusat Pengaturan &amp; Audit Trail</span>
            </div>
            <h1 class="text-2xl font-extrabold text-[#0f172a] tracking-tight">Riwayat Aktivitas Sistem</h1>
            <p class="text-sm text-[#515f74] mt-0.5">Catatan log audit autentikasi, mutasi aset, persetujuan pejabat, dan riwayat keamanan.</p>
        </div>
    </div>

    {{-- 4 kartu metrik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs text-[#515f74] font-medium">Total Log Audit</p>
                <p class="text-2xl font-extrabold text-[#0f172a] mt-1">{{ number_format($totalLog, 0, ',', '.') }}</p>
                <span class="text-[11px] text-[#515f74]">Tersimpan permanen</span>
            </div>
            <div class="w-11 h-11 rounded-lg bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]">
                <span class="material-symbols-outlined text-2xl">storage</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs text-[#515f74] font-medium">Aktivitas Hari Ini</p>
                <p class="text-2xl font-extrabold text-[#0f172a] mt-1">{{ number_format($hariIni, 0, ',', '.') }}</p>
                <span class="text-[11px] text-emerald-700 font-semibold">Tercatat hari ini</span>
            </div>
            <div class="w-11 h-11 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                <span class="material-symbols-outlined text-2xl">today</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs text-[#515f74] font-medium">Perubahan</p>
                <p class="text-2xl font-extrabold text-[#0f172a] mt-1">{{ number_format($perubahan, 0, ',', '.') }}</p>
                <span class="text-[11px] text-amber-700 font-semibold">Tambah, ubah &amp; hapus</span>
            </div>
            <div class="w-11 h-11 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700">
                <span class="material-symbols-outlined text-2xl">edit_note</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs text-[#515f74] font-medium">Otorisasi &amp; Persetujuan</p>
                <p class="text-2xl font-extrabold text-[#0f172a] mt-1">{{ number_format($otorisasi, 0, ',', '.') }}</p>
                <span class="text-[11px] text-[#022448] font-semibold">Tindakan pimpinan</span>
            </div>
            <div class="w-11 h-11 rounded-lg bg-[#d5e3fc] flex items-center justify-center text-[#022448]">
                <span class="material-symbols-outlined text-2xl">approval_delegation</span>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('aktivitas.index') }}" class="no-print bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-xs flex flex-col gap-3 mt-6">
        <div class="flex flex-1 flex-col lg:flex-row items-stretch lg:items-center gap-3">
            <div class="relative flex-1 w-full">
                <span class="material-symbols-outlined absolute left-3 top-2 text-lg text-[#515f74] pointer-events-none">search</span>
                <input class="w-full h-9 pl-9 pr-4 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]" name="cari" value="{{ request('cari') }}" placeholder="Cari nama pengguna, IP address, atau kata kunci..." type="text">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <select class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none" name="modul" onchange="this.form.submit()">
                    <option value="">Semua Modul</option>
                    @foreach ($daftarModul as $m)
                        <option value="{{ $m }}" @selected(request('modul') === $m)>{{ $labelModul[$m] ?? $m }}</option>
                    @endforeach
                </select>
                <select class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none" name="aktivitas" onchange="this.form.submit()">
                    <option value="">Semua Aksi</option>
                    @foreach ($labelAktivitas as $key => $label)
                        <option value="{{ $key }}" @selected(request('aktivitas') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <select class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none" name="waktu" onchange="this.form.submit()">
                    <option value="all" @selected(request('waktu', 'all') === 'all')>Semua Waktu</option>
                    <option value="today" @selected(request('waktu') === 'today')>Hari Ini</option>
                    <option value="7d" @selected(request('waktu') === '7d')>7 Hari Terakhir</option>
                    <option value="30d" @selected(request('waktu') === '30d')>30 Hari Terakhir</option>
                </select>
            </div>
            <a class="h-9 px-3 bg-[#f1f5f9] hover:bg-[#e2e7ff] border border-[#e2e7ff] rounded-lg text-xs font-semibold text-[#515f74] hover:text-[#022448] flex items-center justify-center gap-1 transition-colors" href="{{ route('aktivitas.index') }}">
                <span class="material-symbols-outlined text-base">restart_alt</span>
                <span>Reset Filter</span>
            </a>
        </div>
        <div class="flex items-center gap-2 justify-end border-t pt-3 border-[#e2e7ff]">
            <a href="{{ route('dashboard.ekspor', request()->query()) }}" class="h-9 px-3 bg-[#f1f5f9] hover:bg-[#e2e7ff] border border-[#e2e7ff] rounded-lg text-xs font-semibold text-[#515f74] hover:text-[#022448] flex items-center gap-1.5 transition-colors">
                <span class="material-symbols-outlined text-base">download</span>
                <span>Ekspor Excel / CSV</span>
            </a>
            <button class="h-9 px-3 bg-[#1e3a5f] text-white hover:bg-[#152a45] rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-xs transition-colors" type="button" onclick="window.print()">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                <span>Cetak Laporan Audit PDF</span>
            </button>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[11px] font-bold text-[#515f74] uppercase tracking-wider">
                        <th class="py-3 px-4">Waktu &amp; Tanggal</th>
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Peran</th>
                        <th class="py-3 px-4">Modul Domain</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                        <th class="py-3 px-4">Keterangan Detail Aktivitas</th>
                        <th class="py-3 px-4 text-center no-print">Inspeksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-xs">
                    @forelse ($aktivitas as $a)
                        <tr class="hover:bg-[#f1f5f9]/50 transition-colors">
                            <td class="py-3.5 px-4 text-[11px] text-[#515f74] whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">{{ $a->created_at?->locale('id')->translatedFormat('d M Y, H:i:s') }} WIB</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-[#1e3a5f] text-white font-bold flex items-center justify-center text-[10px] shrink-0">{{ $a->user?->initials() ?? '–' }}</div>
                                    <div>
                                        <p class="font-bold text-[#0f172a]">{{ $a->user?->name ?? 'Sistem' }}</p>
                                        <p class="text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">ID: USR-{{ str_pad($a->user_id ?? 0, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc] text-[#022448] text-[10px] font-bold">{{ $a->user?->role?->name ?? 'Sistem' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#f1f5f9] text-[#515f74] text-[10px] font-medium">{{ $labelModul[$a->module] ?? ($a->module ?? '-') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $badgeAksi[$a->activity] ?? 'bg-slate-200 text-slate-700' }}">{{ $labelAktivitas[$a->activity] ?? $a->activity }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-[#0f172a] font-medium">{{ $a->description }}</p>
                                <p class="text-[11px] text-[#515f74] mt-0.5" style="font-family: 'JetBrains Mono', monospace;">IP: {{ $a->ip_address ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap no-print">
                                <button class="p-1 text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded transition-colors btn-inspeksi" title="Detail Log" type="button"
                                    data-waktu="{{ $a->created_at?->locale('id')->translatedFormat('d M Y, H:i:s') }}"
                                    data-nama="{{ $a->user?->name ?? 'Sistem' }}"
                                    data-email="{{ $a->user?->email ?? '-' }}"
                                    data-peran="{{ $a->user?->role?->name ?? 'Sistem' }}"
                                    data-modul="{{ $labelModul[$a->module] ?? ($a->module ?? '-') }}"
                                    data-aksi="{{ $labelAktivitas[$a->activity] ?? $a->activity }}"
                                    data-deskripsi="{{ $a->description }}"
                                    data-ip="{{ $a->ip_address ?? '-' }}"
                                    data-agen="{{ $a->user_agent ?? '-' }}">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-4 text-center text-sm text-[#515f74]">Tidak ada rekaman audit untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
            <div class="flex items-center gap-2">
                <span>Menampilkan <span class="font-bold text-[#0f172a]">{{ $aktivitas->firstItem() ?? 0 }} - {{ $aktivitas->lastItem() ?? 0 }}</span> dari <span class="font-bold text-[#0f172a]">{{ number_format($aktivitas->total(), 0, ',', '.') }}</span> rekaman audit</span>
                <span class="text-[#94a3b8]">|</span>
                <form method="GET" action="{{ route('aktivitas.index') }}" class="flex items-center gap-1.5">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <span>Baris per halaman:</span>
                    <select name="per_halaman" onchange="this.form.submit()" class="h-7 px-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs text-[#0f172a]">
                        @foreach ([10, 25, 50] as $n)
                            <option value="{{ $n }}" @selected($aktivitas->perPage() == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-1">
                @if ($aktivitas->onFirstPage())
                    <span class="px-2.5 h-8 rounded border border-[#e2e7ff] flex items-center text-[#94a3b8] cursor-not-allowed gap-1"><span class="material-symbols-outlined text-base">chevron_left</span><span class="text-xs font-medium">Sebelumnya</span></span>
                @else
                    <a href="{{ $aktivitas->previousPageUrl() }}" class="px-2.5 h-8 rounded border border-[#e2e7ff] flex items-center hover:bg-[#f1f5f9] gap-1"><span class="material-symbols-outlined text-base">chevron_left</span><span class="text-xs font-medium">Sebelumnya</span></a>
                @endif
                @foreach ($aktivitas->getUrlRange(max(1, min($aktivitas->currentPage() - 1, $aktivitas->lastPage() - 2)), min($aktivitas->lastPage(), max(3, $aktivitas->currentPage() + 1))) as $page => $url)
                    @if ($page == $aktivitas->currentPage())
                        <span class="w-8 h-8 rounded bg-[#022448] text-white font-bold flex items-center justify-center text-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded border border-[#e2e7ff] text-[#515f74] hover:bg-[#f1f5f9] flex items-center justify-center text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($aktivitas->lastPage() > 3 && $aktivitas->currentPage() < $aktivitas->lastPage() - 1)
                    <span class="px-1 text-xs text-[#515f74]">...</span>
                    <a href="{{ $aktivitas->url($aktivitas->lastPage()) }}" class="w-8 h-8 rounded border border-[#e2e7ff] text-[#515f74] hover:bg-[#f1f5f9] flex items-center justify-center text-xs">{{ $aktivitas->lastPage() }}</a>
                @endif
                @if ($aktivitas->hasMorePages())
                    <a href="{{ $aktivitas->nextPageUrl() }}" class="px-2.5 h-8 rounded border border-[#e2e7ff] flex items-center hover:bg-[#f1f5f9] gap-1"><span class="text-xs font-medium">Berikutnya</span><span class="material-symbols-outlined text-base">chevron_right</span></a>
                @else
                    <span class="px-2.5 h-8 rounded border border-[#e2e7ff] flex items-center text-[#94a3b8] gap-1"><span class="text-xs font-medium">Berikutnya</span><span class="material-symbols-outlined text-base">chevron_right</span></span>
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

    {{-- Modal inspeksi --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#283044]/40 backdrop-blur-sm hidden no-print" id="modalInspeksi">
        <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 bg-[#f1f5f9]">
                <h3 class="text-base font-bold text-[#0f172a]">Detail Rekaman Audit</h3>
                <button class="p-1 rounded-lg text-[#515f74] hover:text-[#0f172a] hover:bg-[#e2e7ff] transition-colors" onclick="document.getElementById('modalInspeksi').classList.add('hidden')" type="button">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <dl class="p-5 grid grid-cols-3 gap-x-3 gap-y-2.5 text-xs">
                <dt class="text-[#515f74] font-medium">Waktu</dt><dd class="col-span-2 text-[#0f172a] font-semibold" id="inspWaktu" style="font-family: 'JetBrains Mono', monospace;">-</dd>
                <dt class="text-[#515f74] font-medium">Pengguna</dt><dd class="col-span-2 text-[#0f172a] font-semibold" id="inspNama">-</dd>
                <dt class="text-[#515f74] font-medium">Email</dt><dd class="col-span-2 text-[#0f172a]" id="inspEmail">-</dd>
                <dt class="text-[#515f74] font-medium">Peran</dt><dd class="col-span-2 text-[#0f172a]" id="inspPeran">-</dd>
                <dt class="text-[#515f74] font-medium">Modul</dt><dd class="col-span-2 text-[#0f172a]" id="inspModul">-</dd>
                <dt class="text-[#515f74] font-medium">Aksi</dt><dd class="col-span-2 text-[#0f172a]" id="inspAksi">-</dd>
                <dt class="text-[#515f74] font-medium">Deskripsi</dt><dd class="col-span-2 text-[#0f172a]" id="inspDeskripsi">-</dd>
                <dt class="text-[#515f74] font-medium">IP</dt><dd class="col-span-2 text-[#0f172a]" id="inspIp" style="font-family: 'JetBrains Mono', monospace;">-</dd>
                <dt class="text-[#515f74] font-medium">User Agent</dt><dd class="col-span-2 text-[#0f172a] break-all" id="inspAgen">-</dd>
            </dl>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-inspeksi').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var d = btn.dataset;
                document.getElementById('inspWaktu').textContent = (d.waktu || '-') + ' WIB';
                document.getElementById('inspNama').textContent = d.nama;
                document.getElementById('inspEmail').textContent = d.email;
                document.getElementById('inspPeran').textContent = d.peran;
                document.getElementById('inspModul').textContent = d.modul;
                document.getElementById('inspAksi').textContent = d.aksi;
                document.getElementById('inspDeskripsi').textContent = d.deskripsi;
                document.getElementById('inspIp').textContent = d.ip;
                document.getElementById('inspAgen').textContent = d.agen;
                document.getElementById('modalInspeksi').classList.remove('hidden');
            });
        });
    </script>
</x-layouts::app>
