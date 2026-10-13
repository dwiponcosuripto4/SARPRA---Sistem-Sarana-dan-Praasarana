<x-layouts::app :title="__('Pusat Pelaporan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pelaporan" judulHalaman="Pelaporan">
    @php
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
        $rupiahM = fn ($n) => $n >= 1000000
            ? 'Rp '.number_format($n / 1000000, 2, ',', '.').' M'
            : $rupiah($n);
        $pctBaik = ($baik + $tindak) > 0 ? round($baik / ($baik + $tindak) * 100, 1) : 0;
        $ikonJenis = [
            'inventaris' => 'inventory_2', 'pengadaan' => 'shopping_bag', 'kebutuhan' => 'assignment',
            'pemeliharaan' => 'build_circle', 'kondisi' => 'verified',
        ];
    @endphp

    <style>
        @media print {
            aside, header, footer, .no-print { display: none !important; }
            main { padding: 0 !important; }
        }
    </style>

    {{-- Judul --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-[#e2e7ff]">
        <div class="flex flex-col">
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-[#1e3a5f] text-white">Modul Pelaporan Eksekutif</span>
                <span class="text-xs text-[#515f74] font-medium" style="font-family: 'JetBrains Mono', monospace;">• TA {{ now()->locale('id')->translatedFormat('Y') }}/{{ now()->addYear()->locale('id')->translatedFormat('Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Pusat Pelaporan Sarana &amp; Prasarana</h1>
            <p class="text-xs text-[#475569] mt-1">Pilih jenis laporan, tentukan rentang parameter filter, cetak dan ekspor rekap data aset institusi secara terpadu.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-white border border-[#e2e7ff] text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded-lg text-xs font-semibold flex items-center gap-2 shadow-xs transition-colors no-print">
                <span class="material-symbols-outlined text-lg">print</span>
                <span>Cetak</span>
            </button>
            <a href="{{ route('pelaporan.ekspor', request()->query()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold flex items-center gap-2 shadow-sm transition-colors no-print">
                <span class="material-symbols-outlined text-lg">download</span>
                <span>Export Excel / PDF</span>
            </a>
        </div>
    </div>

    {{-- Pilih jenis laporan --}}
    <div class="flex flex-col gap-3 mt-6">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-[#515f74] flex items-center gap-1.5">
                <span class="material-symbols-outlined text-lg text-[#022448]">category</span>
                <span>Pilih Jenis Laporan (5 Kategori Utama)</span>
            </span>
            <span class="text-xs text-[#515f74]">Laporan aktif: <strong class="text-[#022448] font-bold">{{ $jenisLaporan[$jenis]['label'] }}</strong></span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach ($jenisLaporan as $key => $jl)
                <a href="{{ route('pelaporan.index', ['jenis' => $key]) }}" class="p-3.5 rounded-xl border-2 {{ $jenis === $key ? 'border-[#022448] bg-white shadow-sm' : 'border-[#e2e7ff] bg-white hover:border-[#2d4d7a]/50 shadow-xs' }} flex flex-col text-left transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg {{ $jenis === $key ? 'bg-[#1e3a5f] text-white' : 'bg-[#f1f5f9] text-[#515f74]' }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">{{ $ikonJenis[$key] }}</span>
                        </div>
                        <span class="w-2 h-2 rounded-full {{ $jenis === $key ? 'bg-emerald-500' : 'bg-emerald-500 hidden' }}"></span>
                    </div>
                    <span class="font-bold text-xs text-[#0f172a] mb-0.5">{{ $jl['label'] }}</span>
                    <span class="text-[11px] text-[#515f74] leading-snug">{{ $jl['desc'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Parameter filter --}}
    <form method="GET" action="{{ route('pelaporan.index') }}" class="no-print bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col gap-4 mt-6">
        <input type="hidden" name="jenis" value="{{ $jenis }}">
        <div class="flex items-center justify-between border-b border-[#e2e7ff] pb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#022448] text-xl">tune</span>
                <span class="text-xs font-bold text-[#0f172a] uppercase tracking-wider">Parameter &amp; Filter Data Laporan</span>
            </div>
            <a href="{{ route('pelaporan.index', ['jenis' => $jenis]) }}" class="text-[#515f74] hover:text-[#022448] text-xs font-semibold flex items-center gap-1 transition-colors">
                <span class="material-symbols-outlined text-base">restart_alt</span>
                <span>Reset Filter</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-[#515f74] flex items-center gap-1"><span class="material-symbols-outlined text-base">calendar_today</span><span>Rentang Periode / Waktu</span></label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="dari" value="{{ request('dari') }}" class="w-full h-9 px-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full h-9 px-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-[#515f74] flex items-center gap-1"><span class="material-symbols-outlined text-base">devices</span><span>Kategori Barang / Aset</span></label>
                <select name="kategori" class="w-full h-9 px-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" @selected((string) request('kategori') === (string) $k->id)>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-[#515f74] flex items-center gap-1"><span class="material-symbols-outlined text-base">location_city</span><span>Lokasi &amp; Gedung / Satker</span></label>
                <select name="lokasi" class="w-full h-9 px-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none cursor-pointer">
                    <option value="">Semua Lokasi / Unit</option>
                    @foreach ($lokasis as $l)
                        <option value="{{ $l->id }}" @selected((string) request('lokasi') === (string) $l->id)>{{ $l->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-[#515f74] flex items-center gap-1"><span class="material-symbols-outlined text-base">health_and_safety</span><span>Kondisi Aset / Status</span></label>
                <select name="kondisi" class="w-full h-9 px-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none cursor-pointer">
                    <option value="">Semua Kondisi &amp; Status</option>
                    <option value="Baik" @selected(request('kondisi') === 'Baik')>Kondisi Baik</option>
                    <option value="Rusak Ringan" @selected(request('kondisi') === 'Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(request('kondisi') === 'Rusak Berat')>Rusak Berat</option>
                </select>
            </div>
        </div>
        <div class="flex items-center justify-between pt-2 border-t border-[#e2e7ff]">
            <div class="flex items-center gap-2 text-xs text-[#515f74]">
                <span class="material-symbols-outlined text-lg text-emerald-600">check_circle</span>
                <span>Filter siap diaplikasikan pada <strong>{{ number_format($totalData, 0, ',', '.') }} total rekaman</strong></span>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-[#1e3a5f] hover:bg-[#022448] text-white rounded-lg text-xs font-bold flex items-center gap-2 shadow-xs transition-colors">
                <span class="material-symbols-outlined text-lg">filter_alt</span>
                <span>Tampilkan Laporan</span>
            </button>
        </div>
    </form>

    {{-- 4 KPI --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74]">Data Ditemukan</span>
                <span class="text-2xl font-bold text-[#0f172a] mt-0.5" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($totalData, 0, ',', '.') }}</span>
                <span class="text-[11px] text-emerald-700 font-medium mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-sm">check</span>Sesuai parameter kriteria</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#f1f5f9] text-[#022448] flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">format_list_bulleted</span>
            </div>
        </div>
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74]">Total Valuasi Terdata</span>
                <span class="text-2xl font-bold text-[#022448] mt-0.5" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiahM($totalNilai) }}</span>
                <span class="text-[11px] text-[#515f74] font-medium mt-1">Estimasi nilai buku akumulatif</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">payments</span>
            </div>
        </div>
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74]">Aset Kondisi Baik</span>
                <span class="text-2xl font-bold text-emerald-700 mt-0.5" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($baik, 0, ',', '.') }} Unit</span>
                <span class="text-[11px] text-[#515f74] font-medium mt-1">{{ number_format($pctBaik, 1, ',', '.') }}% Siap pakai optimal</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">verified</span>
            </div>
        </div>
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74]">Perlu Tindak Lanjut</span>
                <span class="text-2xl font-bold text-[#dc2626] mt-0.5" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($tindak, 0, ',', '.') }} Unit</span>
                <span class="text-[11px] text-[#dc2626] font-medium mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-sm">warning</span>Perlu servis / afkir</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fef2f2] text-[#dc2626] flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">build</span>
            </div>
        </div>
    </div>

    {{-- Tabel pratinjau --}}
    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('pelaporan.index') }}" class="p-4 border-b border-[#e2e7ff] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <input type="hidden" name="jenis" value="{{ $jenis }}">
            @foreach (request()->except('cari', 'page', 'jenis', 'per_halaman') as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#f1f5f9] flex items-center justify-center text-[#022448]">
                    <span class="material-symbols-outlined text-lg">table_chart</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f172a]">Tabel Pratinjau: {{ $jenisLaporan[$jenis]['label'] }}</h2>
                    <p class="text-xs text-[#515f74] mt-0.5">Menampilkan baris hasil penelusuran parameter terpadu</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-2.5 text-base text-[#515f74]">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari dalam tabel..." class="w-56 h-8 pl-8 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-bold">
                    <tr>
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Kode Aset / Usulan</th>
                        <th class="py-3 px-4">Nama Barang / Kegiatan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Lokasi / Satker</th>
                        <th class="py-3 px-4">Kondisi / Status</th>
                        <th class="py-3 px-4 text-right">Nilai / Estimasi Biaya</th>
                        <th class="py-3 px-4 text-center no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a] font-medium">
                    @forelse ($data as $i => $row)
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3 px-4 text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ str_pad(($data->firstItem() ?? 1) + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-4 font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $row['kode'] }}</td>
                            <td class="py-3 px-4 font-semibold">{{ $row['nama'] }}</td>
                            <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-[#f1f5f9] text-[#515f74] text-[11px] font-semibold">{{ $row['kategori'] }}</span></td>
                            <td class="py-3 px-4 text-[#515f74]">{{ $row['lokasi'] }}</td>
                            <td class="py-3 px-4">
                                @if ($row['kondisi'] === 'baik')
                                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 border border-emerald-600/20 px-2 py-0.5 rounded-full text-[11px]"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ $row['status'] }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-bold text-amber-700 bg-amber-50 border border-amber-600/20 px-2 py-0.5 rounded-full text-[11px]"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>{{ $row['status'] }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-bold" style="font-family: 'JetBrains Mono', monospace;">{{ $row['nilai'] }}</td>
                            <td class="py-3 px-4 text-center no-print">
                                <button type="button" title="Lihat Detail" class="btn-detail p-1 text-[#515f74] hover:text-[#022448] rounded hover:bg-[#f1f5f9] transition-colors" data-row="{{ e(json_encode($row)) }}">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-4 text-center text-sm text-[#515f74]">Tidak ada data untuk parameter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
            <div class="flex items-center gap-2">
                <span>Menampilkan <strong>{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ number_format($data->total(), 0, ',', '.') }}</strong> rekaman</span>
                <span class="text-[#94a3b8]">|</span>
                <form method="GET" action="{{ route('pelaporan.index') }}" class="flex items-center gap-1.5">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <span>Baris per halaman:</span>
                    <select name="per_halaman" onchange="this.form.submit()" class="bg-[#f1f5f9] border border-[#e2e7ff] rounded px-1.5 py-0.5 text-xs text-[#0f172a] font-semibold focus:outline-none">
                        @foreach ([10, 25, 50] as $n)
                            <option value="{{ $n }}" @selected($data->perPage() == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-1">
                @if ($data->onFirstPage())
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed"><span class="material-symbols-outlined text-sm">chevron_left</span></span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9]"><span class="material-symbols-outlined text-sm">chevron_left</span></a>
                @endif
                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 1), min($data->lastPage(), $data->currentPage() + 1)) as $page => $url)
                    @if ($page == $data->currentPage())
                        <span class="px-2.5 py-1 rounded bg-[#022448] text-white font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-semibold">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($data->lastPage() > 3 && $data->currentPage() < $data->lastPage() - 1)
                    <span class="px-1 text-[#515f74]">...</span>
                    <a href="{{ $data->url($data->lastPage()) }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-semibold">{{ $data->lastPage() }}</a>
                @endif
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9]"><span class="material-symbols-outlined text-sm">chevron_right</span></a>
                @else
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed"><span class="material-symbols-outlined text-sm">chevron_right</span></span>
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

    {{-- Modal detail baris --}}
    <div id="modalBaris" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#022448]/60 backdrop-blur-xs hidden no-print">
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl border border-[#e2e7ff] overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-[#e2e7ff] flex items-center justify-between bg-[#f1f5f9]/50">
                <h3 class="text-base font-bold text-[#0f172a]">Detail Rekaman</h3>
                <button type="button" onclick="document.getElementById('modalBaris').classList.add('hidden')" class="p-1 text-[#515f74] hover:text-[#0f172a] rounded-lg hover:bg-[#e2e7ff] transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <dl class="p-5 grid grid-cols-3 gap-x-3 gap-y-2.5 text-xs" id="detailBarisIsi"></dl>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = JSON.parse(btn.dataset.row);
                const label = {kode: 'Kode', nama: 'Nama', kategori: 'Kategori', lokasi: 'Lokasi', status: 'Status', nilai: 'Nilai'};
                document.getElementById('detailBarisIsi').innerHTML = Object.entries(row)
                    .filter(([k]) => label[k])
                    .map(([k, v]) => `<dt class="text-[#515f74] font-medium">${label[k]}</dt><dd class="col-span-2 text-[#0f172a] font-semibold">${v}</dd>`)
                    .join('');
                document.getElementById('modalBaris').classList.remove('hidden');
            });
        });
    </script>
</x-layouts::app>
