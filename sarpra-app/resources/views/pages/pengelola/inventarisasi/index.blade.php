<x-layouts::app :title="__('Inventarisasi Aset')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="inventarisasi" judulHalaman="Inventarisasi">
    @php
        $pctBaik = ($baik + $ringan + $berat) > 0 ? round($baik / ($baik + $ringan + $berat) * 100, 1) : 0;
        $kondisiBadge = [
            'Baik' => ['kelas' => 'bg-[#dcfce7] text-[#16a34a] border-[#86efac]', 'dot' => 'bg-[#16a34a]'],
            'Rusak Ringan' => ['kelas' => 'bg-[#fef3c7] text-[#d97706] border-[#fcd34d]', 'dot' => 'bg-[#d97706]'],
            'Rusak Berat' => ['kelas' => 'bg-[#fee2e2] text-[#dc2626] border-[#fca5a5]', 'dot' => 'bg-[#dc2626]'],
        ];
    @endphp

    {{-- Judul --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-[#e2e7ff]">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Inventarisasi Aset</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#d5e3fc] text-[#57657a] border border-[#e2e7ff]" style="font-family: 'JetBrains Mono', monospace;">SARPRA Asset Registry</span>
            </div>
            <p class="text-sm text-[#515f74]">Pencatatan, kodifikasi, verifikasi fisik, dan pemantauan kondisi seluruh sarana dan prasarana terpadu.</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('inventarisasi.ekspor', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-[#e2e7ff] rounded-lg text-xs font-semibold text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] transition-all shadow-xs">
                <span class="material-symbols-outlined text-lg">file_download</span>
                <span>Ekspor Data (CSV)</span>
            </a>
            <button type="button" onclick="bukaModalTambah()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e3a5f] hover:bg-[#152e4f] text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                <span>+ Tambah Barang</span>
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ecfdf5] border border-emerald-200 rounded-xl">
            <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0">check_circle</span>
            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-xl">
            <span class="material-symbols-outlined text-[#ba1a1a] text-xl shrink-0">error</span>
            <p class="text-sm text-[#93000a]">{{ session('error') }}</p>
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
        <div class="bg-white p-4 rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Total Barang Inventaris</span>
                <span class="text-2xl font-extrabold text-[#0f172a] mt-1">{{ number_format($totalUnit, 0, ',', '.') }} <span class="text-xs font-medium text-[#515f74]">Unit</span></span>
                <span class="text-[11px] text-[#515f74] mt-1">{{ $jumlahKategori }} Kategori Terdaftar</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]">
                <span class="material-symbols-outlined text-2xl">inventory_2</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Kondisi Baik</span>
                <span class="text-2xl font-extrabold text-[#16a34a] mt-1">{{ number_format($baik, 0, ',', '.') }} <span class="text-xs font-medium text-[#515f74]">Unit</span></span>
                <span class="text-[11px] font-semibold text-[#16a34a] mt-1 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-[#16a34a]"></span> {{ number_format($pctBaik, 1, ',', '.') }}% Siap Digunakan</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                <span class="material-symbols-outlined text-2xl">verified</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Rusak Ringan</span>
                <span class="text-2xl font-extrabold text-[#d97706] mt-1">{{ number_format($ringan, 0, ',', '.') }} <span class="text-xs font-medium text-[#515f74]">Unit</span></span>
                <span class="text-[11px] font-semibold text-[#d97706] mt-1 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-[#d97706]"></span> Perlu Servis / Pemeliharaan</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#fef3c7] flex items-center justify-center text-[#d97706]">
                <span class="material-symbols-outlined text-2xl">build</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Rusak Berat</span>
                <span class="text-2xl font-extrabold text-[#dc2626] mt-1">{{ number_format($berat, 0, ',', '.') }} <span class="text-xs font-medium text-[#515f74]">Unit</span></span>
                <span class="text-[11px] font-semibold text-[#dc2626] mt-1 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-[#dc2626]"></span> Usul Penghapusan / Rekondisi</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#dc2626]">
                <span class="material-symbols-outlined text-2xl">warning</span>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('inventarisasi.index') }}" class="bg-white p-4 rounded-xl border border-[#e2e7ff] shadow-xs flex flex-col gap-3.5 mt-4">
        <div class="flex flex-col lg:flex-row items-center gap-3">
            <div class="relative flex-1 w-full flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode barang, nama inventaris, merk/model..." class="w-full h-10 pl-9 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
                @if (request('cari'))
                    <a href="{{ route('inventarisasi.index', request()->except('cari', 'page')) }}" class="absolute right-2.5 text-[#515f74] hover:text-[#0f172a]"><span class="material-symbols-outlined text-base">close</span></a>
                @endif
            </div>
            <div class="flex items-center gap-2 w-full lg:w-auto flex-wrap sm:flex-nowrap">
                <select name="kategori" onchange="this.form.submit()" class="h-10 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] focus:outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" @selected((string) request('kategori') === (string) $k->id)>{{ $k->name }}</option>
                    @endforeach
                </select>
                <select name="lokasi" onchange="this.form.submit()" class="h-10 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] focus:outline-none cursor-pointer">
                    <option value="">Semua Lokasi</option>
                    @foreach ($lokasis as $l)
                        <option value="{{ $l->id }}" @selected((string) request('lokasi') === (string) $l->id)>{{ $l->name }}</option>
                    @endforeach
                </select>
                <select name="kondisi" onchange="this.form.submit()" class="h-10 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] focus:outline-none cursor-pointer">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" @selected(request('kondisi') === 'Baik')>Kondisi Baik</option>
                    <option value="Rusak Ringan" @selected(request('kondisi') === 'Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(request('kondisi') === 'Rusak Berat')>Rusak Berat</option>
                </select>
                <select name="status" onchange="this.form.submit()" class="h-10 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] focus:outline-none cursor-pointer">
                    <option value="">Semua Status Aset</option>
                    @foreach ($statuss as $s)
                        <option value="{{ $s->name }}" @selected(request('status') === $s->name)>{{ $s->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('inventarisasi.index') }}" title="Reset Filter" class="h-10 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#515f74] hover:text-[#022448] hover:bg-[#e2e7ff] transition-all flex items-center gap-1 font-semibold">
                    <span class="material-symbols-outlined text-base">restart_alt</span>
                    <span>Reset</span>
                </a>
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-xs overflow-hidden mt-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] text-[11px] uppercase tracking-wider">
                        <th class="p-3.5 font-bold">Kode Barang</th>
                        <th class="p-3.5 font-bold">Nama Barang &amp; Spesifikasi</th>
                        <th class="p-3.5 font-bold">Kategori</th>
                        <th class="p-3.5 font-bold">Lokasi Penempatan</th>
                        <th class="p-3.5 font-bold text-center">Kondisi Fisik</th>
                        <th class="p-3.5 font-bold text-center">Status Operasional</th>
                        <th class="p-3.5 font-bold">Perolehan / Cek</th>
                        <th class="p-3.5 pr-5 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-xs text-[#0f172a]">
                    @forelse ($data as $b)
                        @php $kb = $kondisiBadge[$b->kondisi?->name] ?? ['kelas' => 'bg-[#f1f5f9] text-[#515f74]', 'dot' => 'bg-[#94a3b8]']; @endphp
                        <tr class="hover:bg-[#f1f5f9]/70 transition-colors {{ ($b->kondisi?->name ?? '') === 'Rusak Berat' ? 'bg-red-50/20' : '' }}">
                            <td class="p-3.5 font-semibold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $b->kode_barang }}</td>
                            <td class="p-3.5">
                                <div class="flex flex-col">
                                    <span class="font-bold">{{ $b->nama_barang }}</span>
                                    <span class="text-[11px] text-[#515f74] mt-0.5">{{ \Illuminate\Support\Str::limit(($b->merk ? $b->merk.' • ' : '').($b->spesifikasi ?? '-'), 70) }}</span>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[11px] font-semibold border border-blue-200">{{ $b->kategori?->name ?? '-' }}</span>
                            </td>
                            <td class="p-3.5">
                                <div class="flex items-center gap-1.5 text-[#515f74]">
                                    <span class="material-symbols-outlined text-[15px] text-[#94a3b8]">meeting_room</span>
                                    <span>{{ $b->lokasi?->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $kb['kelas'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $kb['dot'] }}"></span> {{ $b->kondisi?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $b->status?->name ?? '-' }}</span>
                            </td>
                            <td class="p-3.5 text-[#515f74]">
                                <div class="flex flex-col" style="font-family: 'JetBrains Mono', monospace;">
                                    <span class="text-[11px]">{{ $b->tahun_perolehan ?? '-' }}</span>
                                    <span class="text-[#94a3b8] text-[10px]">Cek: {{ $b->updated_at?->locale('id')->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="p-3.5 pr-5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" title="Detail" class="btn-detail p-1 text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded"
                                        data-kode="{{ $b->kode_barang }}" data-nama="{{ $b->nama_barang }}"
                                        data-merk="{{ $b->merk ?? '-' }}" data-kategori="{{ $b->kategori?->name }}"
                                        data-lokasi="{{ $b->lokasi?->name }}" data-kondisi="{{ $b->kondisi?->name }}"
                                        data-status="{{ $b->status?->name }}" data-jumlah="{{ $b->jumlah }} {{ $b->satuan?->name }}"
                                        data-tahun="{{ $b->tahun_perolehan ?? '-' }}" data-sumber="{{ $b->sumber_perolehan ?? '-' }}"
                                        data-harga="Rp {{ number_format($b->harga_perolehan, 0, ',', '.') }}"
                                        data-spesifikasi="{{ $b->spesifikasi ?? '-' }}">
                                        <span class="material-symbols-outlined text-[17px]">visibility</span>
                                    </button>
                                    <button type="button" title="Edit Data" class="btn-ubah p-1 text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded"
                                        data-id="{{ $b->id }}" data-nama="{{ $b->nama_barang }}" data-merk="{{ $b->merk }}"
                                        data-kategori="{{ $b->kategori_barang_id }}" data-lokasi="{{ $b->lokasi_id }}"
                                        data-kondisi="{{ $b->kondisi_barang_id }}" data-status="{{ $b->status_barang_id }}"
                                        data-jumlah="{{ $b->jumlah }}" data-satuan="{{ $b->satuan_id }}"
                                        data-tahun="{{ $b->tahun_perolehan }}" data-sumber="{{ $b->sumber_perolehan }}"
                                        data-harga="{{ $b->harga_perolehan }}" data-spesifikasi="{{ $b->spesifikasi }}">
                                        <span class="material-symbols-outlined text-[17px]">edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('inventarisasi.destroy', $b) }}" class="inline" onsubmit="return confirm('Hapus {{ addslashes($b->kode_barang) }} dari inventaris?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="p-1 text-[#515f74] hover:text-[#dc2626] hover:bg-red-50 rounded">
                                            <span class="material-symbols-outlined text-[17px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-sm text-[#515f74]">Belum ada data barang untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
            <div class="flex items-center gap-3">
                <span>Menampilkan <span class="font-bold text-[#0f172a]">{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</span> dari <span class="font-bold text-[#0f172a]">{{ number_format($data->total(), 0, ',', '.') }}</span> barang inventaris</span>
                <span class="text-[#94a3b8]">|</span>
                <form method="GET" action="{{ route('inventarisasi.index') }}" class="flex items-center gap-1.5">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <span>Baris per halaman:</span>
                    <select name="per_halaman" onchange="this.form.submit()" class="bg-[#f1f5f9] border border-[#e2e7ff] rounded px-2 py-1 text-xs text-[#0f172a] focus:outline-none">
                        @foreach ([10, 25, 50] as $n)
                            <option value="{{ $n }}" @selected($data->perPage() == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-1">
                @if ($data->onFirstPage())
                    <span class="p-1.5 border border-[#e2e7ff] rounded text-[#94a3b8] cursor-not-allowed"><span class="material-symbols-outlined text-base">chevron_left</span></span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="p-1.5 border border-[#e2e7ff] rounded hover:bg-[#f1f5f9]"><span class="material-symbols-outlined text-base">chevron_left</span></a>
                @endif
                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 1), min($data->lastPage(), $data->currentPage() + 1)) as $page => $url)
                    @if ($page == $data->currentPage())
                        <span class="px-3 py-1 bg-[#022448] text-white rounded font-bold shadow-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 border border-[#e2e7ff] rounded hover:bg-[#f1f5f9] font-medium">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($data->lastPage() > 3 && $data->currentPage() < $data->lastPage() - 1)
                    <span class="px-1.5 text-[#94a3b8]">...</span>
                    <a href="{{ $data->url($data->lastPage()) }}" class="px-3 py-1 border border-[#e2e7ff] rounded hover:bg-[#f1f5f9] font-medium">{{ $data->lastPage() }}</a>
                @endif
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="p-1.5 border border-[#e2e7ff] rounded hover:bg-[#f1f5f9]"><span class="material-symbols-outlined text-base">chevron_right</span></a>
                @else
                    <span class="p-1.5 border border-[#e2e7ff] rounded text-[#94a3b8] cursor-not-allowed"><span class="material-symbols-outlined text-base">chevron_right</span></span>
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

    {{-- Modal tambah/ubah --}}
    <div id="modalBarang" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-[#e2e7ff] overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 bg-[#1e3a5f] text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-[22px]">post_add</span>
                    <div>
                        <h2 class="font-bold text-base leading-tight" id="modalJudul">Catat Aset Baru / Tambah Barang Inventaris</h2>
                        <p class="text-[11px] text-[#8aa4cf] mt-0.5">Formulir registrasi dan penomoran inventaris sarana dan prasarana</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModal()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <form id="formBarang" method="POST" action="{{ route('inventarisasi.store') }}" enctype="multipart/form-data" class="p-6 overflow-y-auto flex flex-col gap-4 text-xs text-[#0f172a]">
                @csrf
                <div id="methodBarang"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Kode Barang (Otomatis)</label>
                        <input type="text" value="BRG-XXXX (dibuat otomatis)" readonly class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg font-semibold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">
                    </div>
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Kategori Aset <span class="text-[#dc2626]">*</span></label>
                        <select name="kategori_barang_id" id="fKategori" required class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih --</option>
                            @foreach ($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-[#515f74] mb-1">Nama Barang &amp; Merk/Model <span class="text-[#dc2626]">*</span></label>
                    <input type="text" name="nama_barang" id="fNama" required maxlength="150" placeholder="Contoh: Smart TV LG 65 Inch 4K UHD" class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Merk</label>
                        <input type="text" name="merk" id="fMerk" maxlength="100" placeholder="Contoh: Epson" class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Foto Barang</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#022448] file:text-white file:text-xs file:font-semibold hover:file:bg-[#1e3a5f]">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Lokasi Penempatan <span class="text-[#dc2626]">*</span></label>
                        <select name="lokasi_id" id="fLokasi" required class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih --</option>
                            @foreach ($lokasis as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Status Penggunaan <span class="text-[#dc2626]">*</span></label>
                        <select name="status_barang_id" id="fStatus" required class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih --</option>
                            @foreach ($statuss as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Jumlah Unit</label>
                        <input type="number" name="jumlah" id="fJumlah" value="1" min="1" class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Satuan</label>
                        <select name="satuan_id" id="fSatuan" class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                            @foreach ($satuans as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Nilai Perolehan (Rp)</label>
                        <input type="number" name="harga_perolehan" id="fHarga" min="0" step="0.01" placeholder="12500000" class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" id="fTahun" min="1900" max="2100" value="{{ now()->format('Y') }}" class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#515f74] mb-1">Sumber Perolehan</label>
                        <input type="text" name="sumber_perolehan" id="fSumber" maxlength="100" placeholder="APBD / Hibah / ..." class="w-full h-9 px-3 bg-white border border-[#e2e7ff] rounded-lg text-xs focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-[#515f74] mb-1.5">Kondisi Fisik Saat Penerimaan</label>
                    <div class="grid grid-cols-3 gap-2" id="radioKondisi">
                        @foreach ($kondisis as $k)
                            <label class="flex items-center gap-2 p-2 border rounded-lg cursor-pointer text-xs font-semibold {{ $k->name === 'Baik' ? 'border-emerald-300 bg-emerald-50/50 text-[#16a34a]' : ($k->name === 'Rusak Ringan' ? 'border-amber-300 bg-amber-50/50 text-[#d97706]' : 'border-rose-300 bg-rose-50/50 text-[#dc2626]') }}">
                                <input type="radio" name="kondisi_barang_id" value="{{ $k->id }}" @checked($k->name === 'Baik')>
                                <span>{{ $k->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-[#515f74] mb-1">Spesifikasi Tambahan</label>
                    <textarea name="spesifikasi" id="fSpesifikasi" rows="2" placeholder="Kelengkapan, nomor seri, aksesoris, catatan khusus..." class="w-full p-2.5 bg-white border border-[#e2e7ff] rounded-lg text-xs placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]"></textarea>
                </div>
                <div class="px-6 py-3.5 bg-[#f1f5f9] border-t border-[#e2e7ff] flex items-center justify-end gap-2.5 -mx-6 -mb-6 mt-2">
                    <button type="button" onclick="tutupModal()" class="px-4 py-2 border border-[#e2e7ff] bg-white hover:bg-[#f1f5f9] text-[#515f74] text-xs font-semibold rounded-lg transition-colors">Batalkan</button>
                    <button type="submit" class="px-5 py-2 bg-[#1e3a5f] hover:bg-[#152e4f] text-white text-xs font-bold rounded-lg transition-colors shadow-sm inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">save</span>
                        <span>Simpan Barang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal detail --}}
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-lg rounded-xl border border-[#e2e7ff] shadow-lg flex flex-col">
            <div class="px-6 py-4 border-b border-[#e2e7ff] flex items-center justify-between bg-[#f1f5f9]">
                <h2 class="text-base font-bold text-[#0f172a]">Detail Barang</h2>
                <button type="button" onclick="document.getElementById('modalDetail').classList.add('hidden')" class="p-1 text-[#515f74] hover:text-[#0f172a] hover:bg-[#e2e7ff] rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <dl class="p-6 grid grid-cols-3 gap-x-3 gap-y-2.5 text-xs">
                <dt class="text-[#515f74] font-medium">Kode</dt><dd class="col-span-2 font-semibold" id="dKode" style="font-family: 'JetBrains Mono', monospace;">-</dd>
                <dt class="text-[#515f74] font-medium">Nama</dt><dd class="col-span-2 font-semibold" id="dNama">-</dd>
                <dt class="text-[#515f74] font-medium">Merk</dt><dd class="col-span-2" id="dMerk">-</dd>
                <dt class="text-[#515f74] font-medium">Kategori</dt><dd class="col-span-2" id="dKategori">-</dd>
                <dt class="text-[#515f74] font-medium">Lokasi</dt><dd class="col-span-2" id="dLokasi">-</dd>
                <dt class="text-[#515f74] font-medium">Kondisi</dt><dd class="col-span-2" id="dKondisi">-</dd>
                <dt class="text-[#515f74] font-medium">Status</dt><dd class="col-span-2" id="dStatus">-</dd>
                <dt class="text-[#515f74] font-medium">Jumlah</dt><dd class="col-span-2" id="dJumlah">-</dd>
                <dt class="text-[#515f74] font-medium">Perolehan</dt><dd class="col-span-2" id="dPerolehan">-</dd>
                <dt class="text-[#515f74] font-medium">Harga</dt><dd class="col-span-2" id="dHarga">-</dd>
                <dt class="text-[#515f74] font-medium">Spesifikasi</dt><dd class="col-span-2" id="dSpesifikasi">-</dd>
            </dl>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalBarang');
        const form = document.getElementById('formBarang');
        const storeAction = "{{ route('inventarisasi.store') }}";

        function bukaModalTambah() {
            document.getElementById('modalJudul').textContent = 'Catat Aset Baru / Tambah Barang Inventaris';
            form.action = storeAction;
            document.getElementById('methodBarang').innerHTML = '';
            form.reset();
            modal.classList.remove('hidden');
        }

        function tutupModal() {
            modal.classList.add('hidden');
        }

        document.querySelectorAll('.btn-ubah').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('modalJudul').textContent = 'Ubah Data Barang';
                form.action = storeAction + '/' + d.id;
                document.getElementById('methodBarang').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('fNama').value = d.nama;
                document.getElementById('fMerk').value = d.merk;
                document.getElementById('fKategori').value = d.kategori;
                document.getElementById('fLokasi').value = d.lokasi;
                document.getElementById('fStatus').value = d.status;
                document.getElementById('fJumlah').value = d.jumlah;
                document.getElementById('fSatuan').value = d.satuan;
                document.getElementById('fHarga').value = d.harga;
                document.getElementById('fTahun').value = d.tahun;
                document.getElementById('fSumber').value = d.sumber;
                document.getElementById('fSpesifikasi').value = d.spesifikasi;
                form.querySelectorAll('input[name="kondisi_barang_id"]').forEach(r => r.checked = r.value === d.kondisi);
                modal.classList.remove('hidden');
            });
        });

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('dKode').textContent = d.kode;
                document.getElementById('dNama').textContent = d.nama;
                document.getElementById('dMerk').textContent = d.merk;
                document.getElementById('dKategori').textContent = d.kategori;
                document.getElementById('dLokasi').textContent = d.lokasi;
                document.getElementById('dKondisi').textContent = d.kondisi;
                document.getElementById('dStatus').textContent = d.status;
                document.getElementById('dJumlah').textContent = d.jumlah;
                document.getElementById('dPerolehan').textContent = d.tahun + ' • ' + d.sumber;
                document.getElementById('dHarga').textContent = d.harga;
                document.getElementById('dSpesifikasi').textContent = d.spesifikasi;
                document.getElementById('modalDetail').classList.remove('hidden');
            });
        });
    </script>
</x-layouts::app>
