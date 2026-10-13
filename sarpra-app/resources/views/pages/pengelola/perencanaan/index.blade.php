<x-layouts::app :title="__('Perencanaan Kebutuhan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="perencanaan" judulHalaman="Perencanaan">
    @php
        $badgePrioritas = [
            'Tinggi' => 'bg-[#fef2f2] text-[#b91c1c] border-red-200/60',
            'Mendesak' => 'bg-[#fef2f2] text-[#b91c1c] border-red-200/60',
            'Sedang' => 'bg-[#fffbeb] text-[#b45309] border-amber-200/60',
            'Rendah' => 'bg-[#f1f5f9] text-[#475569] border-slate-200/60',
        ];
        $dotPrioritas = [
            'Tinggi' => 'bg-red-600', 'Mendesak' => 'bg-red-600',
            'Sedang' => 'bg-amber-500', 'Rendah' => 'bg-slate-400',
        ];
    @endphp

    {{-- Judul --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-[#e2e7ff]">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-extrabold text-[#0f172a] tracking-tight">Perencanaan Kebutuhan</h1>
                <span class="px-2.5 py-0.5 bg-[#1e3a5f] text-white text-xs font-semibold rounded-full shadow-xs">T.A. {{ now()->locale('id')->translatedFormat('Y') }} / {{ now()->addYear()->locale('id')->translatedFormat('Y') }}</span>
            </div>
            <p class="text-xs text-[#515f74] mt-1 font-medium">Rencana usulan pengadaan dan peremajaan sarana prasarana tahun anggaran mendatang.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('perencanaan.ekspor', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-[#515f74] hover:text-[#022448] bg-white border border-[#e2e7ff] rounded-lg shadow-xs hover:bg-[#f1f5f9] transition-colors">
                <span class="material-symbols-outlined text-base">file_download</span> Unduh Rekap (CSV)
            </a>
            <button type="button" onclick="bukaModalTambah()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#022448] hover:bg-[#1e3a5f] text-white text-xs font-bold rounded-lg shadow-sm transition-all">
                <span class="material-symbols-outlined text-lg">add</span>Tambah Kebutuhan
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
        <div class="p-4 bg-white rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#515f74] uppercase tracking-wider">Total Usulan</p>
                <h3 class="text-xl font-extrabold text-[#0f172a] mt-0.5">{{ $totalUsulan }} <span class="text-xs font-medium text-[#515f74]">Usulan</span></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-[#f1f5f9] flex items-center justify-center text-[#022448]">
                <span class="material-symbols-outlined text-[22px]">assignment</span>
            </div>
        </div>
        <div class="p-4 bg-white rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#515f74] uppercase tracking-wider">Prioritas Tinggi</p>
                <h3 class="text-xl font-extrabold text-[#dc2626] mt-0.5">{{ $prioritasTinggi }} <span class="text-xs font-medium text-[#515f74]">Kebutuhan</span></h3>
                <p class="text-[10px] text-[#dc2626] font-semibold mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-xs">priority_high</span> Mendesak operasional</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#dc2626]">
                <span class="material-symbols-outlined text-[22px]">error</span>
            </div>
        </div>
        <div class="p-4 bg-white rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#515f74] uppercase tracking-wider">Perlu Kelengkapan Data</p>
                <h3 class="text-xl font-extrabold text-[#0f172a] mt-0.5">{{ $draft }} <span class="text-xs font-medium text-[#515f74]">Draft</span></h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-[#f1f5f9] flex items-center justify-center text-[#515f74]">
                <span class="material-symbols-outlined text-[22px]">pending_actions</span>
            </div>
        </div>
        <div class="p-4 bg-white rounded-xl border border-[#e2e7ff] shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-[#515f74] uppercase tracking-wider">Sudah Diajukan</p>
                <h3 class="text-xl font-extrabold text-[#022448] mt-0.5">{{ $diajukan }} <span class="text-xs font-medium text-[#515f74]">Terkirim</span></h3>
                <p class="text-[10px] text-emerald-600 font-semibold mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-xs">check_circle</span> Menunggu persetujuan</p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <span class="material-symbols-outlined text-[22px]">send</span>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('perencanaan.index') }}" class="p-3 bg-white rounded-xl border border-[#e2e7ff] shadow-xs flex flex-col md:flex-row items-stretch md:items-center gap-2 mt-4">
        <div class="relative flex-1 min-w-[260px] flex items-center">
            <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama kebutuhan atau catatan..." class="w-full h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
        </div>
        <select name="kategori" onchange="this.form.submit()" class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer">
            <option value="">Semua Kategori</option>
            @foreach ($kategoris as $k)
                <option value="{{ $k->id }}" @selected((string) request('kategori') === (string) $k->id)>{{ $k->name }}</option>
            @endforeach
        </select>
        <select name="periode" onchange="this.form.submit()" class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer">
            <option value="">Semua Periode</option>
            @foreach ($periodes as $p)
                <option value="{{ $p->id }}" @selected((string) request('periode') === (string) $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="diajukan" @selected(request('status') === 'diajukan')>Diajukan</option>
        </select>
        <a href="{{ route('perencanaan.index') }}" title="Reset Filter" class="h-9 px-2.5 bg-[#f1f5f9] hover:bg-[#e2e7ff] border border-[#e2e7ff] rounded-lg text-[#515f74] hover:text-[#022448] transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-lg leading-none">filter_alt_off</span>
        </a>
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-xs overflow-hidden mt-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] text-[#515f74] text-[11px] font-bold uppercase tracking-wider border-b border-[#e2e7ff]">
                        <th class="py-3 px-3 text-center w-12">No</th>
                        <th class="py-3 px-4 min-w-[280px]">Nama Kebutuhan &amp; Spesifikasi</th>
                        <th class="py-3 px-4 min-w-[150px]">Kategori</th>
                        <th class="py-3 px-4 text-center min-w-[110px]">Jumlah</th>
                        <th class="py-3 px-4 text-center min-w-[120px]">Prioritas</th>
                        <th class="py-3 px-4 min-w-[130px]">Periode</th>
                        <th class="py-3 px-4 text-center min-w-[110px]">Status</th>
                        <th class="py-3 px-4 text-right min-w-[130px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-xs text-[#0f172a]">
                    @forelse ($data as $i => $row)
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-3 text-center text-[#515f74] font-medium" style="font-family: 'JetBrains Mono', monospace;">{{ str_pad(($data->firstItem() ?? 1) + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold">{{ $row->nama_kebutuhan }}</div>
                                <p class="text-[11px] text-[#515f74] mt-0.5 line-clamp-1">{{ $row->alasan }}</p>
                                <span class="text-[10px] text-[#94a3b8] mt-0.5 block" style="font-family: 'JetBrains Mono', monospace;">Kode: PRN-{{ $row->created_at->format('Y') }}-{{ str_pad($row->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-semibold border border-blue-200/50">{{ $row->kategori?->name ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-extrabold" style="font-family: 'JetBrains Mono', monospace;">{{ $row->jumlah }}</span>
                                <span class="text-[#515f74] text-[11px]">{{ $row->satuan?->name ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $badgePrioritas[$row->prioritas?->name] ?? 'bg-[#f1f5f9] text-[#475569] border-slate-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotPrioritas[$row->prioritas?->name] ?? 'bg-slate-400' }}"></span> {{ $row->prioritas?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-[#515f74]">{{ $row->periode?->name ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row->status === 'diajukan')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#ecfdf5] text-[#047857] text-[11px] font-bold border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Diajukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    @if ($row->status === 'draft')
                                        <button type="button" title="Edit Rencana" class="btn-ubah p-1 text-[#515f74] hover:text-[#022448] hover:bg-[#f1f5f9] rounded transition-colors"
                                            data-id="{{ $row->id }}"
                                            data-nama="{{ $row->nama_kebutuhan }}"
                                            data-kategori="{{ $row->kategori_barang_id }}"
                                            data-periode="{{ $row->periode_id }}"
                                            data-jumlah="{{ $row->jumlah }}"
                                            data-satuan="{{ $row->satuan_id }}"
                                            data-prioritas="{{ $row->prioritas?->name }}"
                                            data-alasan="{{ $row->alasan }}">
                                            <span class="material-symbols-outlined text-[17px]">edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('perencanaan.destroy', $row) }}" class="inline" onsubmit="return confirm('Hapus usulan {{ addslashes($row->nama_kebutuhan) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus" class="p-1 text-[#515f74] hover:text-[#dc2626] hover:bg-red-50 rounded transition-colors">
                                                <span class="material-symbols-outlined text-[17px]">delete</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('perencanaan.ajukan', $row) }}" class="inline">
                                            @csrf
                                            <button type="submit" title="Ajukan Usulan" class="p-1 text-[#022448] hover:bg-[#d5e3fc] rounded transition-colors">
                                                <span class="material-symbols-outlined text-[17px]">send</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" title="Detail Pengajuan" class="btn-detail p-1 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded transition-colors"
                                            data-nama="{{ $row->nama_kebutuhan }}"
                                            data-kategori="{{ $row->kategori?->name }}"
                                            data-jumlah="{{ $row->jumlah }} {{ $row->satuan?->name }}"
                                            data-prioritas="{{ $row->prioritas?->name }}"
                                            data-periode="{{ $row->periode?->name }}"
                                            data-alasan="{{ $row->alasan }}"
                                            data-pengusul="{{ $row->user?->name }}">
                                            <span class="material-symbols-outlined text-[17px]">visibility</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-4 text-center text-sm text-[#515f74]">Belum ada usulan kebutuhan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3.5 bg-[#f1f5f9] border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
            <div class="flex items-center gap-2">
                <span>Menampilkan <strong class="text-[#0f172a]">{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a]">{{ $data->total() }}</strong> usulan kebutuhan</span>
                <span class="text-[#94a3b8]">|</span>
                <form method="GET" action="{{ route('perencanaan.index') }}" class="flex items-center gap-1">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <label class="flex items-center gap-1">Tampilkan
                        <select name="per_halaman" onchange="this.form.submit()" class="bg-white border border-[#e2e7ff] rounded px-1.5 py-0.5 text-xs text-[#0f172a] font-medium focus:outline-none">
                            @foreach ([10, 25, 50] as $n)
                                <option value="{{ $n }}" @selected($data->perPage() == $n)>{{ $n }}</option>
                            @endforeach
                        </select>
                    </label>
                </form>
            </div>
            <div class="flex items-center gap-1">
                @if ($data->onFirstPage())
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed text-xs font-semibold">Sebelumnya</span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="px-2.5 py-1 rounded bg-white border border-[#e2e7ff] hover:bg-[#f1f5f9] text-xs font-semibold transition-colors">Sebelumnya</a>
                @endif
                @foreach ($data->getUrlRange(max(1, $data->currentPage() - 1), min($data->lastPage(), $data->currentPage() + 1)) as $page => $url)
                    @if ($page == $data->currentPage())
                        <span class="px-2.5 py-1 rounded bg-[#022448] text-white font-bold text-xs shadow-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] bg-white hover:bg-[#f1f5f9] font-semibold text-xs transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] bg-white hover:bg-[#f1f5f9] font-semibold text-xs transition-colors">Selanjutnya</a>
                @else
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed text-xs font-semibold">Selanjutnya</span>
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
    <div id="modalKebutuhan" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-2xl rounded-xl border border-[#e2e7ff] shadow-lg flex flex-col max-h-[90vh] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#e2e7ff] flex items-center justify-between bg-[#f1f5f9]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#022448] text-[22px]">post_add</span>
                    <h2 class="text-base font-bold text-[#0f172a]" id="modalJudul">Formulir Usulan Perencanaan Kebutuhan</h2>
                </div>
                <button type="button" onclick="tutupModal()" class="p-1 text-[#515f74] hover:text-[#0f172a] hover:bg-[#e2e7ff] rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <form id="formKebutuhan" method="POST" action="{{ route('perencanaan.store') }}" class="p-6 overflow-y-auto space-y-4 text-xs">
                @csrf
                <div id="methodKebutuhan"></div>
                <div>
                    <label class="block font-bold text-[#0f172a] mb-1">Nama Barang / Kebutuhan <span class="text-[#dc2626]">*</span></label>
                    <input type="text" name="nama_kebutuhan" id="fNama" required maxlength="150" placeholder="Contoh: Laptop Administrasi Staf (Core i5, 16GB RAM)" class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#0f172a] mb-1">Kategori <span class="text-[#dc2626]">*</span></label>
                        <select name="kategori_barang_id" id="fKategori" required class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-[#0f172a] mb-1">Periode Kebutuhan <span class="text-[#dc2626]">*</span></label>
                        <select name="periode_id" id="fPeriode" required class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periodes as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#0f172a] mb-1">Jumlah <span class="text-[#dc2626]">*</span></label>
                        <input type="number" name="jumlah" id="fJumlah" min="1" value="1" required class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                    <div>
                        <label class="block font-bold text-[#0f172a] mb-1">Satuan <span class="text-[#dc2626]">*</span></label>
                        <select name="satuan_id" id="fSatuan" required class="w-full h-9 px-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($satuans as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-[#0f172a] mb-1.5">Prioritas <span class="text-[#dc2626]">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <label class="cursor-pointer flex items-center gap-2 p-2.5 rounded-lg border border-red-200 bg-red-50/50 hover:bg-red-50">
                            <input type="radio" name="prioritas" value="Tinggi" checked class="text-[#dc2626]">
                            <div><span class="block font-bold text-[#dc2626] text-xs">Tinggi</span><span class="block text-[10px] text-[#515f74]">Mendesak operasional</span></div>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2 p-2.5 rounded-lg border border-amber-200 bg-amber-50/50 hover:bg-amber-50">
                            <input type="radio" name="prioritas" value="Sedang" class="text-amber-600">
                            <div><span class="block font-bold text-amber-700 text-xs">Sedang</span><span class="block text-[10px] text-[#515f74]">Kebutuhan reguler</span></div>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2 p-2.5 rounded-lg border border-slate-200 bg-slate-50/50 hover:bg-slate-50">
                            <input type="radio" name="prioritas" value="Rendah" class="text-slate-600">
                            <div><span class="block font-bold text-slate-700 text-xs">Rendah</span><span class="block text-[10px] text-[#515f74]">Peremajaan berkala</span></div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-[#0f172a] mb-1">Alasan Kebutuhan / Keterangan <span class="text-[#dc2626]">*</span></label>
                    <textarea name="alasan" id="fAlasan" required rows="3" placeholder="Jelaskan alasan dan rincian kebutuhan sarana prasarana..." class="w-full p-2.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]"></textarea>
                </div>
                <div class="px-6 py-3.5 border-t border-[#e2e7ff] bg-[#f1f5f9] flex items-center justify-between gap-2 -mx-6 -mb-6 mt-2">
                    <button type="button" onclick="tutupModal()" class="px-3.5 py-2 text-xs font-semibold text-[#515f74] hover:text-[#0f172a] bg-white border border-[#e2e7ff] rounded-lg transition-colors">Batalkan</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#022448] hover:bg-[#1e3a5f] rounded-lg shadow-sm transition-colors">Simpan Kebutuhan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal detail --}}
    <div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-lg rounded-xl border border-[#e2e7ff] shadow-lg flex flex-col">
            <div class="px-6 py-4 border-b border-[#e2e7ff] flex items-center justify-between bg-[#f1f5f9]">
                <h2 class="text-base font-bold text-[#0f172a]">Detail Usulan Kebutuhan</h2>
                <button type="button" onclick="document.getElementById('modalDetail').classList.add('hidden')" class="p-1 text-[#515f74] hover:text-[#0f172a] hover:bg-[#e2e7ff] rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <dl class="p-6 grid grid-cols-3 gap-x-3 gap-y-2.5 text-xs">
                <dt class="text-[#515f74] font-medium">Kebutuhan</dt><dd class="col-span-2 text-[#0f172a] font-semibold" id="dNama">-</dd>
                <dt class="text-[#515f74] font-medium">Kategori</dt><dd class="col-span-2 text-[#0f172a]" id="dKategori">-</dd>
                <dt class="text-[#515f74] font-medium">Jumlah</dt><dd class="col-span-2 text-[#0f172a]" id="dJumlah">-</dd>
                <dt class="text-[#515f74] font-medium">Prioritas</dt><dd class="col-span-2 text-[#0f172a]" id="dPrioritas">-</dd>
                <dt class="text-[#515f74] font-medium">Periode</dt><dd class="col-span-2 text-[#0f172a]" id="dPeriode">-</dd>
                <dt class="text-[#515f74] font-medium">Alasan</dt><dd class="col-span-2 text-[#0f172a]" id="dAlasan">-</dd>
                <dt class="text-[#515f74] font-medium">Pengusul</dt><dd class="col-span-2 text-[#0f172a]" id="dPengusul">-</dd>
            </dl>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalKebutuhan');
        const form = document.getElementById('formKebutuhan');
        const storeAction = form.action;

        function bukaModalTambah() {
            document.getElementById('modalJudul').textContent = 'Formulir Usulan Perencanaan Kebutuhan';
            form.action = storeAction;
            document.getElementById('methodKebutuhan').innerHTML = '';
            form.reset();
            modal.classList.remove('hidden');
        }

        function bukaModalUbahDari(btn) {
            const d = btn.dataset;
            document.getElementById('modalJudul').textContent = 'Ubah Usulan Kebutuhan';
            form.action = storeAction.replace(/perencanaan$/, 'perencanaan/' + d.id);
            document.getElementById('methodKebutuhan').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('fNama').value = d.nama;
            document.getElementById('fKategori').value = d.kategori;
            document.getElementById('fPeriode').value = d.periode;
            document.getElementById('fJumlah').value = d.jumlah;
            document.getElementById('fSatuan').value = d.satuan;
            form.querySelectorAll('input[name="prioritas"]').forEach(r => r.checked = r.value === d.prioritas);
            document.getElementById('fAlasan').value = d.alasan;
            modal.classList.remove('hidden');
        }

        function tutupModal() {
            modal.classList.add('hidden');
        }

        document.querySelectorAll('.btn-ubah').forEach(btn => {
            btn.addEventListener('click', () => bukaModalUbahDari(btn));
        });

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                document.getElementById('dNama').textContent = d.nama;
                document.getElementById('dKategori').textContent = d.kategori;
                document.getElementById('dJumlah').textContent = d.jumlah;
                document.getElementById('dPrioritas').textContent = d.prioritas;
                document.getElementById('dPeriode').textContent = d.periode;
                document.getElementById('dAlasan').textContent = d.alasan;
                document.getElementById('dPengusul').textContent = d.pengusul;
                document.getElementById('modalDetail').classList.remove('hidden');
            });
        });
    </script>
</x-layouts::app>
