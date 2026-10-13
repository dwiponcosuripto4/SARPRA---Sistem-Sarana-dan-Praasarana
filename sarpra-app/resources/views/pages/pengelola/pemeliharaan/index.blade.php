<x-layouts::app :title="__('Manajemen Pemeliharaan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pemeliharaan" judulHalaman="Pemeliharaan">
    @php
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
        $badgeStatus = [
            'diajukan' => ['kelas' => 'bg-amber-50 text-amber-700 border-amber-200', 'ikon' => 'hourglass_top', 'label' => 'Diajukan'],
            'dalam_pemeliharaan' => ['kelas' => 'bg-blue-50 text-blue-700 border-blue-200', 'ikon' => 'sync', 'label' => 'Dalam Pemeliharaan'],
            'selesai' => ['kelas' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'ikon' => 'check_circle', 'label' => 'Selesai'],
            'ditolak' => ['kelas' => 'bg-rose-50 text-rose-700 border-rose-200', 'ikon' => 'cancel', 'label' => 'Ditolak'],
        ];
        $badgeJenis = [
            'Rutin' => 'bg-slate-100 text-slate-700',
            'Berkala' => 'bg-blue-50 text-blue-700',
            'Perbaikan' => 'bg-rose-50 text-rose-700',
            'Darurat' => 'bg-red-100 text-red-800',
            'Kalibrasi' => 'bg-amber-50 text-amber-800',
        ];
    @endphp

    {{-- Judul --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Manajemen Pemeliharaan</h1>
                <span class="px-2.5 py-0.5 rounded-full bg-[#d5e3fc] text-[#57657a] text-xs font-semibold">Modul Servis</span>
            </div>
            <p class="text-sm text-[#515f74] mt-1">Kelola tiket servis, perbaikan kerusakan, pemeliharaan berkala sarana dan prasarana terpadu.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pemeliharaan.ekspor', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-[#e2e7ff] bg-white text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] text-xs font-semibold shadow-xs transition-all">
                <span class="material-symbols-outlined text-lg">download</span>
                <span>Unduh Rekap Servis</span>
            </a>
            <button type="button" onclick="document.getElementById('modalPemeliharaan').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#022448] hover:bg-[#1e3a5f] text-white text-xs font-semibold shadow-sm transition-all">
                <span class="material-symbols-outlined text-lg">add</span>
                <span>Ajukan Pemeliharaan</span>
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ecfdf5] border border-emerald-200 rounded-xl">
            <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0">check_circle</span>
            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-xl">
            <span class="material-symbols-outlined text-[#ba1a1a] text-xl shrink-0">error</span>
            <p class="text-sm text-[#93000a]">{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-[#515f74]">Total Tiket Servis</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-[#0f172a]">{{ $totalTiket }}</span>
                    <span class="text-xs font-semibold text-[#515f74]">Tiket</span>
                </div>
                <p class="text-[11px] text-[#515f74] mt-1" style="font-family: 'JetBrains Mono', monospace;">Est. {{ $rupiah($totalEstimasi) }}</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]">
                <span class="material-symbols-outlined text-2xl">build_circle</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-[#515f74]">Diajukan (Review)</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-amber-600">{{ $diajukan }}</span>
                    <span class="text-xs font-semibold text-[#515f74]">Tiket</span>
                </div>
                <p class="text-[11px] text-amber-600 font-medium mt-1">Perlu verifikasi teknis</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-200">
                <span class="material-symbols-outlined text-2xl">pending_actions</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-[#515f74]">Dalam Pemeliharaan</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-blue-600">{{ $dalamProses }}</span>
                    <span class="text-xs font-semibold text-[#515f74]">Tiket</span>
                </div>
                <p class="text-[11px] text-blue-600 font-medium mt-1">Sedang ditangani teknisi</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-200">
                <span class="material-symbols-outlined text-2xl">handyman</span>
            </div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-[#515f74]">Selesai (Bulan Ini)</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-emerald-600">{{ $selesaiBulanIni }}</span>
                    <span class="text-xs font-semibold text-[#515f74]">Tiket</span>
                </div>
                <p class="text-[11px] text-emerald-600 font-medium mt-1">Aset siap digunakan</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-200">
                <span class="material-symbols-outlined text-2xl">task_alt</span>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('pemeliharaan.index') }}" class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between mt-4">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-lg text-[#515f74] pointer-events-none">search</span>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode, nama aset, nomor tiket, atau teknisi..." class="w-full h-9 pl-9 pr-4 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <select name="jenis" onchange="this.form.submit()" class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none cursor-pointer">
                <option value="">Semua Jenis</option>
                @foreach ($jeniss as $j)
                    <option value="{{ $j->id }}" @selected((string) request('jenis') === (string) $j->id)>{{ $j->name }}</option>
                @endforeach
            </select>
            <select name="status" onchange="this.form.submit()" class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="diajukan" @selected(request('status') === 'diajukan')>Diajukan</option>
                <option value="dalam_pemeliharaan" @selected(request('status') === 'dalam_pemeliharaan')>Dalam Pemeliharaan</option>
                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
            </select>
            <select name="prioritas" onchange="this.form.submit()" class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none cursor-pointer">
                <option value="">Semua Prioritas</option>
                <option value="Tinggi" @selected(request('prioritas') === 'Tinggi')>Tinggi</option>
                <option value="Sedang" @selected(request('prioritas') === 'Sedang')>Sedang</option>
                <option value="Rendah" @selected(request('prioritas') === 'Rendah')>Rendah</option>
            </select>
            <a href="{{ route('pemeliharaan.index') }}" class="h-9 px-3 border border-[#e2e7ff] rounded-lg bg-[#f1f5f9] hover:bg-[#e2e7ff] text-[#515f74] text-xs font-semibold flex items-center gap-1 transition-colors">
                <span class="material-symbols-outlined text-base">restart_alt</span>
                <span>Reset</span>
            </a>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="rounded-xl bg-white border border-[#e2e7ff] shadow-xs overflow-hidden mt-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-[#f1f5f9]/70 border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase text-[11px] tracking-wider">
                        <th class="py-3.5 px-4">No. Tiket &amp; Tanggal</th>
                        <th class="py-3.5 px-4">Barang &amp; Lokasi</th>
                        <th class="py-3.5 px-4">Jenis Pemeliharaan</th>
                        <th class="py-3.5 px-4">Kerusakan / Masalah</th>
                        <th class="py-3.5 px-4 text-right">Estimasi Biaya</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff]">
                    @forelse ($data as $t)
                        @php $st = $badgeStatus[$t->status] ?? $badgeStatus['diajukan']; @endphp
                        <tr class="hover:bg-[#f1f5f9]/40 transition-colors">
                            <td class="py-3.5 px-4 font-medium text-[#022448]">
                                <div style="font-family: 'JetBrains Mono', monospace;">{{ $t->nomor_pemeliharaan ?? 'MNT-' }}</div>
                                <div class="text-[11px] text-[#515f74]">{{ $t->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-[#0f172a]">{{ $t->barang?->nama_barang ?? '-' }}</div>
                                <div class="text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $t->barang?->kode_barang ?? '-' }} • {{ $t->barang?->lokasi?->name ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#f1f5f9] text-[#475569] font-semibold text-[10px] border border-[#e2e7ff]">
                                    {{ $t->jenis?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-[#475569] truncate" title="{{ $t->masalah }}">{{ $t->masalah }}</td>
                            <td class="py-3.5 px-4 font-bold text-right text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">
                                Rp {{ number_format($t->estimasi_biaya, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $st['kelas'] }}">
                                    <span class="material-symbols-outlined text-[13px]">{{ $st['ikon'] }}</span> {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" class="btn-detail p-1 text-[#515f74] hover:text-[#022448] rounded hover:bg-[#f1f5f9]" title="Detail Tiket" data-id="{{ $t->id }}">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    @if ($t->status === 'diajukan')
                                        <form method="POST" action="{{ route('pemeliharaan.proses', $t) }}" class="inline" title="Mulai Pengerjaan">
                                            @csrf
                                            <button type="submit" class="p-1 text-blue-600 hover:bg-blue-50 rounded">
                                                <span class="material-symbols-outlined text-lg">play_arrow</span>
                                            </button>
                                        </form>
                                        <button type="button" title="Edit Tiket" class="btn-ubah p-1 text-[#515f74] hover:text-[#022448] rounded hover:bg-[#f1f5f9]"
                                            data-id="{{ $t->id }}" data-jenis="{{ $t->jenis_pemeliharaan_id }}"
                                            data-prioritas="{{ $t->prioritas?->name }}" data-kerusakan="{{ $t->masalah }}"
                                            data-estimasi="{{ $t->estimasi_biaya }}">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('pemeliharaan.tolak', $t) }}" class="inline" title="Tolak Pengajuan" onsubmit="return confirm('Tolak tiket {{ $t->nomor_pemeliharaan }}?')">
                                            @csrf
                                            <button type="submit" class="p-1 text-[#515f74] hover:text-[#dc2626] hover:bg-red-50 rounded">
                                                <span class="material-symbols-outlined text-lg">block</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('pemeliharaan.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus tiket {{ $t->nomor_pemeliharaan }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Tiket" class="p-1 text-[#515f74] hover:text-[#dc2626] hover:bg-red-50 rounded">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    @elseif ($t->status === 'dalam_pemeliharaan')
                                        <button type="button" title="Selesaikan" class="btn-selesai p-1 text-emerald-600 hover:bg-emerald-50 rounded" data-id="{{ $t->id }}">
                                            <span class="material-symbols-outlined text-lg">task_alt</span>
                                        </button>
                                    @elseif ($t->status === 'selesai')
                                        <button type="button" title="Berita Acara" class="btn-detail p-1 text-emerald-600 rounded hover:bg-emerald-50" data-id="{{ $t->id }}">
                                            <span class="material-symbols-outlined text-lg">receipt_long</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-sm text-[#515f74]">Belum ada tiket pemeliharaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
            <div class="flex items-center gap-2">
                <span>Menampilkan</span>
                <form method="GET" action="{{ route('pemeliharaan.index') }}" class="inline">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="per_halaman" onchange="this.form.submit()" class="h-7 px-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs text-[#0f172a]">
                        @foreach ([10, 25, 50] as $n)
                            <option value="{{ $n }}" @selected($data->perPage() == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
                <span>dari <strong>{{ $data->total() }}</strong> tiket pemeliharaan</span>
            </div>
            <div class="flex items-center gap-1">
                @if ($data->onFirstPage())
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-medium">Sebelumnya</a>
                @endif
                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 1), min($data->lastPage(), $data->currentPage() + 1)) as $page => $url)
                    @if ($page == $data->currentPage())
                        <span class="px-2.5 py-1 rounded bg-[#022448] text-white font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-medium">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-medium">Berikutnya</a>
                @else
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed">Berikutnya</span>
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

    {{-- Modal ajukan/ubah --}}
    <div id="modalPemeliharaan" class="fixed inset-0 z-50 hidden bg-[#022448]/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-[#e2e7ff] w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e2e7ff] flex items-center justify-between bg-[#f1f5f9]/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#022448] text-white flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">build</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#0f172a]" id="modalJudul">Pengajuan Tiket Pemeliharaan Baru</h3>
                        <p class="text-xs text-[#515f74]">Lengkapi formulir untuk mencatat permintaan servis barang</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModal()" class="p-1 text-[#515f74] hover:text-[#022448] rounded-lg hover:bg-[#e2e7ff] transition-colors">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>
            <form id="formPemeliharaan" method="POST" action="{{ route('pemeliharaan.store') }}" enctype="multipart/form-data" class="px-6 py-5 overflow-y-auto flex flex-col gap-4 text-xs">
                @csrf
                <div id="methodPemeliharaan"></div>
                <div id="wrapBarang">
                    <label class="block font-semibold text-[#0f172a] mb-1.5">Pilih Barang dari Inventaris <span class="text-red-500">*</span></label>
                    <select name="barang_id" id="fBarang" required class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                        <option value="">-- Pilih Aset Sarpras --</option>
                        @foreach (App\Models\Barang::orderBy('nama_barang')->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#0f172a] mb-1.5">Jenis Pemeliharaan <span class="text-red-500">*</span></label>
                        <select name="jenis_pemeliharaan_id" id="fJenis" required class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($jeniss as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-[#0f172a] mb-1.5">Tingkat Prioritas <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2" id="radioPrioritas">
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-[#e2e7ff] bg-[#f1f5f9] cursor-pointer text-[#515f74]">
                                <input type="radio" name="prioritas" value="Rendah" class="sr-only">
                                <span class="font-medium text-[11px]">Rendah</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-[#e2e7ff] bg-[#f1f5f9] cursor-pointer text-[#515f74]">
                                <input type="radio" name="prioritas" value="Sedang" checked class="sr-only">
                                <span class="font-medium text-[11px]">Sedang</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-[#e2e7ff] bg-[#f1f5f9] cursor-pointer text-[#515f74]">
                                <input type="radio" name="prioritas" value="Tinggi" class="sr-only">
                                <span class="font-medium text-[11px] text-red-600 font-bold">Darurat</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-[#0f172a] mb-1.5">Deskripsi Kerusakan / Kendala Operasional <span class="text-red-500">*</span></label>
                    <textarea name="kerusakan" id="fKerusakan" rows="3" required placeholder="Jelaskan gejala kerusakan fisik, kode eror, atau kendala pemakaian..." class="w-full p-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#0f172a] mb-1.5">Estimasi Biaya Servis (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-xs font-bold text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">Rp</span>
                            <input type="number" name="estimasi_biaya" id="fEstimasi" min="0" step="0.01" placeholder="Contoh: 500000" class="w-full h-9 pl-10 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-[#0f172a] mb-1.5">Penanggung Jawab / Vendor Servis</label>
                        <input type="text" name="pelaksana" id="fPelaksana" maxlength="150" placeholder="Internal Sarpras / Nama Vendor" class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                </div>
                <div id="wrapFoto">
                    <label class="block font-semibold text-[#0f172a] mb-1.5">Unggah Foto Kerusakan / Bukti Fisik</label>
                    <input type="file" name="foto[]" multiple accept="image/*" class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#022448] file:text-white file:text-xs file:font-semibold hover:file:bg-[#1e3a5f]">
                    <p class="text-[10px] text-[#94a3b8] mt-0.5">Gambar, maks 5 MB per berkas.</p>
                </div>
                <div class="px-6 py-3 border-t border-[#e2e7ff] bg-[#f1f5f9]/40 flex items-center justify-end gap-2.5 -mx-6 -mb-6 mt-2">
                    <button type="button" onclick="tutupModal()" class="px-4 py-2 rounded-lg border border-[#e2e7ff] bg-white text-[#515f74] hover:text-[#0f172a] text-xs font-semibold transition-colors">Batalkan</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#022448] hover:bg-[#1e3a5f] text-white text-xs font-semibold shadow-xs transition-colors">Kirim Pengajuan Pemeliharaan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal selesaikan --}}
    <div id="modalSelesai" class="fixed inset-0 z-50 hidden bg-[#022448]/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-[#e2e7ff] w-full max-w-lg flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e2e7ff] bg-[#f1f5f9]/50">
                <h3 class="text-base font-bold text-[#0f172a]">Selesaikan Tiket Pemeliharaan</h3>
                <p class="text-xs text-[#515f74]">Catat hasil pengerjaan teknisi.</p>
            </div>
            <form id="formSelesai" method="POST" enctype="multipart/form-data" class="px-6 py-5 flex flex-col gap-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-[#0f172a] mb-1.5">Tindakan / Perbaikan <span class="text-red-500">*</span></label>
                    <textarea name="tindakan" rows="3" required placeholder="Uraikan tindakan perbaikan yang dilakukan..." class="w-full p-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-[#0f172a] mb-1.5">Biaya Aktual (Rp)</label>
                        <input type="number" name="biaya_aktual" min="0" step="0.01" class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                    <div>
                        <label class="block font-semibold text-[#0f172a] mb-1.5">Pelaksana</label>
                        <input type="text" name="pelaksana" maxlength="150" class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-[#0f172a] mb-1.5">Dokumentasi Sesudah</label>
                    <input type="file" name="foto_sesudah[]" multiple accept="image/*" class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#022448] file:text-white file:text-xs file:font-semibold hover:file:bg-[#1e3a5f]">
                </div>
                <div class="flex items-center justify-end gap-2.5">
                    <button type="button" onclick="document.getElementById('modalSelesai').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-[#e2e7ff] bg-white text-[#515f74] text-xs font-semibold">Batalkan</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors">Tandai Selesai</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Drawer detail --}}
    <div id="drawerPemeliharaan" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden justify-end">
        <div class="w-full max-w-2xl bg-white h-full shadow-2xl flex flex-col border-l border-[#e2e7ff] overflow-hidden">
            <div class="p-6 border-b border-[#e2e7ff] flex items-start justify-between bg-[#f1f5f9]/40">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-[#1e3a5f] text-white" style="font-family: 'JetBrains Mono', monospace;" id="drNomor">-</span>
                        <span id="drStatus"></span>
                    </div>
                    <h2 class="text-base font-bold text-[#0f172a] mt-1" id="drBarang">-</h2>
                    <p class="text-xs text-[#515f74]">Diajukan oleh: <strong class="text-[#0f172a]" id="drPengaju">-</strong></p>
                </div>
                <button class="p-1.5 rounded-lg text-[#515f74] hover:text-[#022448] hover:bg-[#e2e7ff] transition-colors" onclick="tutupDrawer()" type="button">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">
                <section>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#022448] flex items-center gap-1.5 mb-3"><span class="material-symbols-outlined text-lg">build</span>1. Laporan Kerusakan</h3>
                    <dl class="grid grid-cols-3 gap-x-3 gap-y-2 text-xs">
                        <dt class="text-[#515f74]">Jenis</dt><dd class="col-span-2 font-semibold" id="drJenis">-</dd>
                        <dt class="text-[#515f74]">Prioritas</dt><dd class="col-span-2" id="drPrioritas">-</dd>
                        <dt class="text-[#515f74]">Tanggal</dt><dd class="col-span-2" id="drTanggal">-</dd>
                        <dt class="text-[#515f74]">Masalah</dt><dd class="col-span-2" id="drMasalah">-</dd>
                        <dt class="text-[#515f74]">Estimasi</dt><dd class="col-span-2 font-semibold" id="drEstimasi" style="font-family: 'JetBrains Mono', monospace;">-</dd>
                    </dl>
                </section>
                <section>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#022448] flex items-center gap-1.5 mb-3"><span class="material-symbols-outlined text-lg">folder_open</span>2. Dokumentasi</h3>
                    <div class="flex flex-col gap-2" id="drDokumen"></div>
                </section>
                <section>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#022448] flex items-center gap-1.5 mb-3"><span class="material-symbols-outlined text-lg">history</span>3. Riwayat Penanganan</h3>
                    <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#e2e7ff]" id="drRiwayat"></div>
                </section>
            </div>
            <div class="p-4 border-t border-[#e2e7ff] bg-[#f1f5f9]/40 flex items-center justify-end">
                <button class="px-4 py-2 rounded-lg border border-[#e2e7ff] bg-white text-xs font-semibold text-[#515f74] hover:text-[#0f172a]" onclick="tutupDrawer()" type="button">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const rupiahID = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
        const modal = document.getElementById('modalPemeliharaan');
        const form = document.getElementById('formPemeliharaan');
        const storeAction = form.action;

        function tutupModal() {
            modal.classList.add('hidden');
        }

        document.querySelectorAll('.btn-ubah').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('modalJudul').textContent = 'Ubah Tiket Pemeliharaan';
                form.action = storeAction + '/' + d.id;
                document.getElementById('methodPemeliharaan').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('fJenis').value = d.jenis;
                form.querySelectorAll('input[name="prioritas"]').forEach(r => r.checked = r.value === d.prioritas);
                document.getElementById('fKerusakan').value = d.kerusakan;
                document.getElementById('fEstimasi').value = d.estimasi;
                document.getElementById('wrapBarang').style.display = 'none';
                document.getElementById('wrapFoto').style.display = 'none';
                modal.classList.remove('hidden');
            });
        });

        document.querySelectorAll('.btn-selesai').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('formSelesai').action = storeAction + '/' + btn.dataset.id + '/selesai';
                document.getElementById('modalSelesai').classList.remove('hidden');
            });
        });

        const drawer = document.getElementById('drawerPemeliharaan');

        function tutupDrawer() {
            drawer.classList.add('hidden');
            drawer.classList.remove('flex');
        }

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch('{{ url('pengelolaan/pemeliharaan') }}/' + btn.dataset.id + '/rincian')
                    .then(r => r.json())
                    .then(d => {
                        document.getElementById('drNomor').textContent = d.nomor || '-';
                        document.getElementById('drBarang').textContent = (d.barang || '-') + (d.kode ? ' (' + d.kode + ')' : '');
                        document.getElementById('drPengaju').textContent = (d.pengaju || '-') + (d.tanggal ? ' • ' + d.tanggal : '');
                        document.getElementById('drJenis').textContent = d.jenis || '-';
                        document.getElementById('drPrioritas').textContent = d.prioritas || '-';
                        document.getElementById('drTanggal').textContent = d.tanggal || '-';
                        document.getElementById('drMasalah').textContent = d.masalah || '-';
                        document.getElementById('drEstimasi').textContent = rupiahID(d.estimasi);

                        document.getElementById('drDokumen').innerHTML = d.dokumen.length
                            ? d.dokumen.map(dok => `
                                <div class="p-3 rounded-lg border border-[#e2e7ff] bg-white flex items-center justify-between hover:bg-[#f1f5f9]/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-2xl ${dok.tahap === 'sesudah' ? 'text-emerald-600' : 'text-[#dc2626]'}">image</span>
                                        <div><div class="text-xs font-semibold text-[#0f172a]">${dok.nama}</div>
                                        <div class="text-[10px] text-[#515f74]">Tahap: ${dok.tahap}</div></div>
                                    </div>
                                    <a href="${dok.url}" target="_blank" class="px-2.5 py-1 text-xs font-semibold text-[#022448] hover:bg-[#d5e3fc]/60 rounded border border-[#e2e7ff] inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">download</span>Unduh
                                    </a>
                                </div>`).join('')
                            : '<p class="text-xs text-[#515f74]">Belum ada dokumentasi.</p>';

                        document.getElementById('drRiwayat').innerHTML = d.riwayat.length
                            ? d.riwayat.map(r => `
                                <div class="relative flex items-start gap-3">
                                    <span class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white ring-2 ring-emerald-100"></span>
                                    <div>
                                        <div class="text-xs font-bold text-[#0f172a]">${String(r.status).replaceAll('_', ' ')}</div>
                                        <div class="text-[11px] text-[#515f74]">${r.tanggal || ''} • Oleh <strong class="text-[#0f172a]">${r.oleh || '-'}</strong>${r.pelaksana ? ' • Pelaksana: ' + r.pelaksana : ''}</div>
                                        ${r.tindakan ? `<p class="text-[11px] text-[#0f172a] mt-0.5">Tindakan: ${r.tindakan}</p>` : ''}
                                        ${r.biaya ? `<p class="text-[11px] text-[#515f74]">Biaya: ${rupiahID(r.biaya)}</p>` : ''}
                                        ${r.catatan ? `<p class="text-[11px] text-emerald-800 bg-emerald-50 p-2 rounded mt-1 border border-emerald-200/60">${r.catatan}</p>` : ''}
                                    </div>
                                </div>`).join('')
                            : '<p class="text-xs text-[#515f74]">Belum ada riwayat.</p>';

                        drawer.classList.remove('hidden');
                        drawer.classList.add('flex');
                    });
            });
        });
    </script>
</x-layouts::app>
