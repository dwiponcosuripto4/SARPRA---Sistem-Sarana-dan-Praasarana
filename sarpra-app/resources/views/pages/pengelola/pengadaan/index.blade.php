<x-layouts::app :title="__('Manajemen Pengadaan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pengadaan" judulHalaman="Pengadaan">
    @php
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
        $badgeStatus = [
            'draft' => ['kelas' => 'bg-slate-100 text-slate-700 border-slate-200', 'ikon' => 'edit_note', 'label' => 'Draft'],
            'diajukan' => ['kelas' => 'bg-blue-50 text-blue-700 border-blue-200/60', 'ikon' => 'send', 'label' => 'Diajukan'],
            'menunggu_persetujuan' => ['kelas' => 'bg-amber-50 text-amber-700 border-amber-200/60', 'ikon' => 'hourglass_top', 'label' => 'Menunggu Persetujuan'],
            'disetujui' => ['kelas' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60', 'ikon' => 'check_circle', 'label' => 'Disetujui'],
            'ditolak' => ['kelas' => 'bg-rose-50 text-rose-700 border-rose-200/60', 'ikon' => 'cancel', 'label' => 'Ditolak'],
            'diproses' => ['kelas' => 'bg-blue-50 text-blue-700 border-blue-200/60', 'ikon' => 'sync', 'label' => 'Diproses'],
            'selesai' => ['kelas' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60', 'ikon' => 'task_alt', 'label' => 'Selesai'],
        ];
        $tabLabel = ['semua' => 'Daftar Pengadaan', 'draft' => 'Draft', 'menunggu' => 'Menunggu Persetujuan', 'diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
        $tabCount = ['semua' => $countSemua, 'draft' => $countDraft, 'menunggu' => $countMenunggu, 'diproses' => $countDiproses, 'selesai' => $countSelesai, 'ditolak' => $countDitolak];
    @endphp

    <style>
        @media print {
            aside, header, footer, .no-print { display: none !important; }
            main { padding: 0 !important; }
            #drawerPengadaan { position: static !important; }
        }
    </style>

    {{-- Judul --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-col">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Manajemen Pengadaan</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#d5e3fc] text-[#57657a] border border-[#e2e7ff]">T.A. {{ now()->locale('id')->translatedFormat('Y') }} / {{ now()->addYear()->locale('id')->translatedFormat('Y') }}</span>
                </div>
                <p class="text-xs md:text-sm text-[#515f74] mt-1">Kelola alur pengadaan barang dan jasa, verifikasi spesifikasi teknis, dokumen pendukung, dan riwayat persetujuan.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pengadaan.ekspor', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-[#e2e7ff] bg-white text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] text-xs font-semibold shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-lg">file_download</span>Ekspor Laporan
                </a>
                <a href="{{ route('pengadaan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#022448] hover:bg-[#1e3a5f] text-white text-xs font-semibold shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-lg">add</span>Buat Pengadaan Baru
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="flex items-start gap-3 p-4 bg-[#ecfdf5] border border-emerald-200 rounded-xl">
                <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0">check_circle</span>
                <p class="text-sm text-emerald-800">{{ session('status') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 p-4 bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-xl">
                <span class="material-symbols-outlined text-[#ba1a1a] text-xl shrink-0">error</span>
                <p class="text-sm text-[#93000a]">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-3 p-4 bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-xl">
                <span class="material-symbols-outlined text-[#ba1a1a] text-xl shrink-0">error</span>
                <p class="text-sm text-[#93000a]">{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- 4 kartu statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Total Pengadaan</span>
                    <div class="w-8 h-8 rounded-lg bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]"><span class="material-symbols-outlined text-lg">shopping_bag</span></div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-bold text-[#0f172a] tracking-tight">{{ $countSemua }} <span class="text-xs font-semibold text-[#515f74]">Paket</span></div>
                    <div class="text-xs font-medium text-[#515f74] mt-1" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiah($totalEstimasi) }}</div>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Menunggu Persetujuan</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-700"><span class="material-symbols-outlined text-lg">hourglass_top</span></div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-bold text-amber-600 tracking-tight">{{ $countMenunggu }} <span class="text-xs font-semibold text-[#515f74]">Usulan</span></div>
                    <div class="text-xs font-medium text-[#515f74] mt-1">Tahap verifikasi PPK &amp; Pimpinan</div>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Sedang Diproses</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-700"><span class="material-symbols-outlined text-lg">sync</span></div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-bold text-blue-600 tracking-tight">{{ $countDiproses }} <span class="text-xs font-semibold text-[#515f74]">Paket</span></div>
                    <div class="text-xs font-medium text-[#515f74] mt-1">Pengadaan aktif / Tender berjalan</div>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Pengadaan Selesai</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-700"><span class="material-symbols-outlined text-lg">task_alt</span></div>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-bold text-emerald-600 tracking-tight">{{ $countSelesai }} <span class="text-xs font-semibold text-[#515f74]">Paket</span></div>
                    <div class="text-xs font-medium text-[#515f74] mt-1">BAST terbit &amp; aset terdaftar</div>
                </div>
            </div>
        </div>

        {{-- Tab status --}}
        <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden">
            <div class="px-5 pt-4 border-b border-[#e2e7ff] flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 overflow-x-auto pb-3">
                    @foreach ($tabLabel as $key => $label)
                        <a href="{{ route('pengadaan.index', array_merge(request()->except('tab', 'page'), ['tab' => $key])) }}" class="px-3 py-1.5 rounded-lg text-xs transition-colors whitespace-nowrap {{ $tab === $key ? 'font-bold bg-[#022448] text-white shadow-xs' : 'font-semibold text-[#515f74] hover:bg-[#f1f5f9]' }}">{{ $label }} ({{ $tabCount[$key] }})</a>
                    @endforeach
                </div>
                <div class="text-xs text-[#515f74] pb-3">Sinkronisasi SAP: <span class="font-medium text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ now()->locale('id')->translatedFormat('H:i') }} WIB</span></div>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('pengadaan.index') }}" class="p-4 bg-[#f1f5f9]/50 border-b border-[#e2e7ff] flex flex-wrap items-center gap-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex-1 min-w-[240px] max-w-md flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nomor pengadaan, paket, atau pemohon..." class="w-full h-9 pl-9 pr-3 bg-white border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#022448]">
                </div>
                <select name="jenis" onchange="this.form.submit()" class="h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] font-medium focus:outline-none cursor-pointer">
                    <option value="">Semua Jenis Pengadaan</option>
                    @foreach ($jeniss as $j)
                        <option value="{{ $j->id }}" @selected((string) request('jenis') === (string) $j->id)>{{ $j->name }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] font-medium focus:outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    @foreach (['draft' => 'Draft', 'diajukan' => 'Diajukan', 'menunggu_persetujuan' => 'Menunggu Persetujuan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                <a href="{{ route('pengadaan.index') }}" class="h-9 px-3 text-xs font-medium text-[#515f74] hover:text-[#022448] transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">restart_alt</span>Reset
                </a>
            </form>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-[#f1f5f9]/70 border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="p-3.5">No. Pengadaan</th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5">Nama Paket &amp; Lingkup</th>
                            <th class="p-3.5">Jenis</th>
                            <th class="p-3.5">Estimasi Biaya</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                        @forelse ($data as $p)
                            @php
                                $st = $badgeStatus[$p->status] ?? $badgeStatus['draft'];
                                $paket = $p->detail->first();
                            @endphp
                            <tr class="hover:bg-[#f1f5f9]/40 transition-colors">
                                <td class="p-3.5 font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $p->nomor_pengadaan }}</td>
                                <td class="p-3.5 text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $p->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}</td>
                                <td class="p-3.5">
                                    <div class="font-bold">{{ $paket?->nama_barang ?? '-' }}</div>
                                    <div class="text-[11px] text-[#515f74] mt-0.5">{{ \Illuminate\Support\Str::limit($p->alasan, 60) }}</div>
                                </td>
                                <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-[#f1f5f9] text-[#515f74] font-medium text-[11px]">{{ $p->jenis?->name ?? '-' }}</span></td>
                                <td class="p-3.5 font-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiah($p->detail->sum('subtotal')) }}</td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $st['kelas'] }}">
                                        <span class="material-symbols-outlined text-sm">{{ $st['ikon'] }}</span>{{ $st['label'] }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" title="Detail" data-id="{{ $p->id }}" class="btn-detail inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-[#e2e7ff] bg-white text-[#022448] hover:bg-[#d5e3fc]/50 font-semibold text-xs shadow-xs transition-colors">
                                            <span class="material-symbols-outlined text-base">visibility</span>Detail
                                        </button>
                                        @if ($p->status === 'draft')
                                            <form method="POST" action="{{ route('pengadaan.kirim', $p) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Kirim Pengajuan" class="p-1.5 text-[#022448] hover:bg-[#d5e3fc]/50 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-lg">send</span>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('pengadaan.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus draft {{ $p->nomor_pengadaan }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Draft" class="p-1.5 text-[#515f74] hover:text-[#dc2626] hover:bg-red-50 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-sm text-[#515f74]">Belum ada data pengadaan pada tab ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
                <div class="flex items-center gap-2">
                    <span>Menampilkan <strong class="text-[#0f172a]">{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a]">{{ $data->total() }}</strong> paket pengadaan</span>
                    <span class="mx-2">•</span>
                    <form method="GET" action="{{ route('pengadaan.index') }}" class="flex items-center gap-1">
                        @foreach (request()->except('per_halaman', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <span class="flex items-center gap-1">Baris:
                            <select name="per_halaman" onchange="this.form.submit()" class="bg-white border border-[#e2e7ff] rounded px-1.5 py-0.5 text-xs text-[#0f172a] font-medium">
                                @foreach ([10, 25, 50] as $n)
                                    <option value="{{ $n }}" @selected($data->perPage() == $n)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </span>
                    </form>
                </div>
                <div class="flex items-center gap-1">
                    @if ($data->onFirstPage())
                        <span class="p-1.5 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed"><span class="material-symbols-outlined text-base">chevron_left</span></span>
                    @else
                        <a href="{{ $data->previousPageUrl() }}" class="p-1.5 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9]"><span class="material-symbols-outlined text-base">chevron_left</span></a>
                    @endif
                    @foreach ($data->getUrlRange(max(1, $data->currentPage() - 1), min($data->lastPage(), $data->currentPage() + 1)) as $page => $url)
                        @if ($page == $data->currentPage())
                            <span class="px-2.5 py-1 rounded bg-[#022448] text-white font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-medium">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($data->hasMorePages())
                        <a href="{{ $data->nextPageUrl() }}" class="p-1.5 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9]"><span class="material-symbols-outlined text-base">chevron_right</span></a>
                    @else
                        <span class="p-1.5 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed"><span class="material-symbols-outlined text-base">chevron_right</span></span>
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
    </div>

    {{-- Drawer detail --}}
    <div id="drawerPengadaan" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden justify-end">
        <div class="w-full max-w-2xl bg-white h-full shadow-2xl flex flex-col border-l border-[#e2e7ff] overflow-hidden">
            <div class="p-6 border-b border-[#e2e7ff] flex items-start justify-between bg-[#f1f5f9]/40">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-[#1e3a5f] text-white" style="font-family: 'JetBrains Mono', monospace;" id="drNomor">-</span>
                        <span id="drStatus"></span>
                    </div>
                    <h2 class="text-base font-bold text-[#0f172a] mt-1" id="drPaket">-</h2>
                    <p class="text-xs text-[#515f74]">Diajukan oleh: <strong class="text-[#0f172a]" id="drPengaju">-</strong></p>
                </div>
                <button class="p-1.5 rounded-lg text-[#515f74] hover:text-[#022448] hover:bg-[#e2e7ff] transition-colors" onclick="tutupDrawer()" type="button">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#022448] flex items-center gap-1.5"><span class="material-symbols-outlined text-lg">devices</span>1. Spesifikasi Teknis Barang</h3>
                        <span class="text-[11px] text-[#515f74] font-medium" id="drJumlahItem"></span>
                    </div>
                    <div class="border border-[#e2e7ff] rounded-lg overflow-hidden">
                        <table class="w-full text-xs">
                            <thead class="bg-[#f1f5f9] font-semibold text-[#515f74] border-b border-[#e2e7ff]">
                                <tr><th class="p-2.5 text-left">Komponen / Spesifikasi</th><th class="p-2.5 text-center">Jumlah</th><th class="p-2.5 text-right">Harga Satuan</th><th class="p-2.5 text-right">Subtotal</th></tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]" id="drDetail"></tbody>
                            <tfoot class="bg-[#f1f5f9]/70 font-semibold border-t border-[#e2e7ff]">
                                <tr><td class="p-2.5 text-right text-[#515f74]" colspan="3">Total Estimasi RAB:</td><td class="p-2.5 text-right font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;" id="drTotal"></td></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="mt-2.5 p-3 rounded-lg bg-[#f1f5f9]/60 border border-[#e2e7ff] text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#515f74] text-lg">description</span>
                        <span class="text-[#515f74]" id="drAlasan"></span>
                    </div>
                </section>
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#022448] flex items-center gap-1.5"><span class="material-symbols-outlined text-lg">folder_open</span>2. Dokumen &amp; Berkas Terlampir</h3>
                        <span class="text-[11px] text-[#515f74] font-medium" id="drJumlahDok"></span>
                    </div>
                    <div class="flex flex-col gap-2" id="drDokumen"></div>
                </section>
                <section>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#022448] flex items-center gap-1.5"><span class="material-symbols-outlined text-lg">history</span>3. Riwayat Persetujuan &amp; Alur Kerja</h3>
                    </div>
                    <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#e2e7ff]" id="drRiwayat"></div>
                </section>
            </div>
            <div class="p-4 border-t border-[#e2e7ff] bg-[#f1f5f9]/40 flex items-center justify-between gap-3">
                <button class="px-4 py-2 rounded-lg border border-[#e2e7ff] bg-white text-xs font-semibold text-[#515f74] hover:text-[#0f172a] hover:bg-[#f1f5f9] transition-colors" onclick="tutupDrawer()" type="button">Tutup</button>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-2 rounded-lg border border-[#e2e7ff] bg-white text-xs font-semibold text-[#022448] hover:bg-[#d5e3fc]/50 inline-flex items-center gap-1.5 transition-colors" type="button" onclick="window.print()">
                        <span class="material-symbols-outlined text-base">print</span>Cetak Formulir
                    </button>
                    <a href="#" id="drUnduh" class="px-4 py-2 rounded-lg bg-[#022448] hover:bg-[#1e3a5f] text-xs font-semibold text-white inline-flex items-center gap-1.5 shadow-sm transition-colors">
                        <span class="material-symbols-outlined text-base">download_for_offline</span>Unduh Dokumen Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rupiahID = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');

        const drawer = document.getElementById('drawerPengadaan');

        function tutupDrawer() {
            drawer.classList.add('hidden');
            drawer.classList.remove('flex');
        }

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch('{{ url('pengelolaan/pengadaan') }}/' + btn.dataset.id + '/rincian')
                    .then(r => r.json())
                    .then(d => {
                        document.getElementById('drNomor').textContent = d.nomor;
                        document.getElementById('drPaket').textContent = (d.detail[0] ? d.detail[0].nama : '-') + (d.detail.length > 1 ? ' (+' + (d.detail.length - 1) + ' item)' : '');
                        document.getElementById('drPengaju').textContent = d.pengaju + (d.tanggal ? ' • ' + d.tanggal : '');
                        document.getElementById('drAlasan').textContent = d.alasan || '-';
                        document.getElementById('drJumlahItem').textContent = d.detail.length + ' Item Total';
                        document.getElementById('drTotal').textContent = rupiahID(d.total);

                        document.getElementById('drDetail').innerHTML = d.detail.map(it => `
                            <tr>
                                <td class="p-2.5"><div class="font-semibold">${it.nama}</div><div class="text-[11px] text-[#515f74]">${it.spesifikasi || '-'}</div></td>
                                <td class="p-2.5 text-center" style="font-family: 'JetBrains Mono', monospace;">${it.jumlah} ${it.satuan || ''}</td>
                                <td class="p-2.5 text-right" style="font-family: 'JetBrains Mono', monospace;">${rupiahID(it.harga)}</td>
                                <td class="p-2.5 text-right font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">${rupiahID(it.subtotal)}</td>
                            </tr>`).join('');

                        document.getElementById('drJumlahDok').textContent = d.dokumen.length + ' Berkas Sah';
                        document.getElementById('drDokumen').innerHTML = d.dokumen.length
                            ? d.dokumen.map(dok => `
                                <div class="p-3 rounded-lg border border-[#e2e7ff] bg-white flex items-center justify-between hover:bg-[#f1f5f9]/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-[#dc2626] text-2xl">picture_as_pdf</span>
                                        <div><div class="text-xs font-semibold text-[#0f172a]">${dok.nama}</div></div>
                                    </div>
                                    <a href="${dok.url}" target="_blank" class="px-2.5 py-1 text-xs font-semibold text-[#022448] hover:bg-[#d5e3fc]/60 rounded border border-[#e2e7ff] inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">download</span>Unduh
                                    </a>
                                </div>`).join('')
                            : '<p class="text-xs text-[#515f74]">Belum ada dokumen terlampir.</p>';

                        document.getElementById('drRiwayat').innerHTML = d.riwayat.length
                            ? d.riwayat.map(r => `
                                <div class="relative flex items-start gap-3">
                                    <span class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white ring-2 ring-emerald-100"></span>
                                    <div>
                                        <div class="text-xs font-bold text-[#0f172a]">${r.status.replaceAll('_', ' ')}</div>
                                        <div class="text-[11px] text-[#515f74]">${r.waktu || ''} • Oleh <strong class="text-[#0f172a]">${r.oleh || '-'}</strong></div>
                                        ${r.catatan ? `<p class="text-[11px] text-emerald-800 bg-emerald-50 p-2 rounded mt-1 border border-emerald-200/60">${r.catatan}</p>` : ''}
                                    </div>
                                </div>`).join('')
                            : '<p class="text-xs text-[#515f74]">Belum ada riwayat.</p>';

                        document.getElementById('drUnduh').href = '{{ url('pengelolaan/pengadaan') }}/' + btn.dataset.id + '/ekspor-rincian';

                        drawer.classList.remove('hidden');
                        drawer.classList.add('flex');
                    });
            });
        });
    </script>
</x-layouts::app>
