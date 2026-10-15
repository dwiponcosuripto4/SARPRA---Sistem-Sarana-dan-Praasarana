<x-layouts::app :title="__('Kelola Data Master')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="master" judulHalaman="Data Master">
    {{-- Judul --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-extrabold text-[#0f172a] tracking-tight">Kelola Data Master</h1>
                    <span class="px-2 py-0.5 rounded text-xs font-bold bg-[#d5e3fc] text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">Standard V2.4</span>
                </div>
                <p class="text-sm text-[#515f74] leading-relaxed">Pusat standarisasi dan kamus data klasifikasi inventaris, lokasi prasarana, serta parameter operasional SARPRA.</p>
            </div>
            <div class="flex items-center flex-wrap gap-2.5">
                <a href="{{ route('master.ekspor') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-[#e2e7ff] text-[#0f172a] hover:bg-[#f1f5f9] transition-colors shadow-xs">
                    <span class="material-symbols-outlined text-lg text-[#515f74]">download</span>Ekspor Kamus Data
                </a>
                <button type="button" onclick="bukaModalTambah()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg bg-[#022448] text-white hover:bg-[#1e3a5f] transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-lg">add_circle</span>Tambah Data Baru
                </button>
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

        {{-- Tab entitas --}}
        <div class="border-b border-[#e2e7ff] overflow-x-auto">
            <div class="flex items-center gap-1 min-w-max pb-1">
                @foreach ($daftar as $key => $entitas)
                    <a href="{{ route('master.index', ['entitas' => $key]) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs transition-all {{ $kunci === $key ? 'font-bold border-b-2 border-[#022448] text-[#022448]' : 'font-medium text-[#515f74] hover:text-[#0f172a]' }}">
                        <span class="material-symbols-outlined text-lg">{{ $entitas['ikon'] }}</span>{{ $entitas['label'] }}<span class="px-1.5 py-0.2 rounded-full {{ $kunci === $key ? 'bg-[#d5e3fc] text-[#022448]' : 'bg-[#f1f5f9] text-[#515f74]' }} text-[10px]" style="font-family: 'JetBrains Mono', monospace;">{{ $counts[$key] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-[#515f74]">Total {{ $daftar[$kunci]['label'] }}</span>
                    <span class="text-2xl font-bold text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ $data->total() }}</span>
                    <span class="text-[11px] text-emerald-600 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">check</span> Terdaftar di master</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]">
                    <span class="material-symbols-outlined text-2xl">layers</span>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-[#515f74]">Terintegrasi Data</span>
                    <span class="text-2xl font-bold text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($totalTerintegrasi, 0, ',', '.') }}</span>
                    <span class="text-[11px] text-[#022448] font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">sync</span> Baris relasi tercatat</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]">
                    <span class="material-symbols-outlined text-2xl">devices_other</span>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-[#515f74]">Total Entitas</span>
                    <span class="text-2xl font-bold text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ count($daftar) }}</span>
                    <span class="text-[11px] text-[#515f74] font-medium">Kamus data SARPRA</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-[#e2e7ff] shadow-xs flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-semibold text-[#515f74]">Terakhir Diperbarui</span>
                    <span class="text-base font-bold text-[#0f172a] mt-1" style="font-family: 'JetBrains Mono', monospace;">{{ $terakhirDiperbarui ? \Carbon\Carbon::parse($terakhirDiperbarui)->locale('id')->translatedFormat('d M Y, H:i') : '-' }}</span>
                    <span class="text-[11px] text-[#515f74] font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">person</span> Data master SARPRA</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#d5e3fc]/60 flex items-center justify-center text-[#022448]">
                    <span class="material-symbols-outlined text-2xl">update</span>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl border border-[#e2e7ff] shadow-xs flex flex-col">
            <form method="GET" action="{{ route('master.index') }}" class="p-4 border-b border-[#e2e7ff] flex flex-col md:flex-row md:items-center gap-3">
                <input type="hidden" name="entitas" value="{{ $kunci }}">
                <div class="relative flex-1 max-w-md flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama {{ strtolower($daftar[$kunci]['label']) }}..." class="w-full h-9 pl-9 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#022448]">
                    @if (request('cari'))
                        <a href="{{ route('master.index', ['entitas' => $kunci]) }}" class="absolute right-2.5 text-[#515f74] hover:text-[#022448]"><span class="material-symbols-outlined text-base">close</span></a>
                    @endif
                </div>
                <button type="submit" class="hidden">Cari</button>
                <button type="button" onclick="bukaModalTambah()" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-lg bg-[#022448] text-white hover:bg-[#1e3a5f] transition-colors md:ml-auto">
                    <span class="material-symbols-outlined text-lg">add</span>Tambah {{ $daftar[$kunci]['label'] }} Baru
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[11px] font-bold text-[#515f74] uppercase tracking-wider">
                        <tr>
                            <th class="p-3 pl-4">Nama &amp; Keterangan</th>
                            <th class="p-3">Relasi Data</th>
                            <th class="p-3">Diperbarui</th>
                            <th class="p-3 pr-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e7ff] text-xs text-[#0f172a]">
                        @forelse ($data as $row)
                            <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                                <td class="p-3 pl-4">
                                    <div class="font-bold">{{ $row->name }}</div>
                                    <div class="text-[11px] text-[#515f74]">{{ $row->description ?? '-' }}</div>
                                </td>
                                <td class="p-3 font-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($relasiCount[$row->id] ?? 0, 0, ',', '.') }} baris</td>
                                <td class="p-3 text-[#515f74] text-[11px]">{{ $row->updated_at?->locale('id')->translatedFormat('d M Y') }}</td>
                                <td class="p-3 pr-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" title="Edit Data" onclick="bukaModalUbah({{ $row->id }}, {{ Js::from($row->name) }}, {{ Js::from($row->description) }})" class="p-1 text-[#515f74] hover:text-[#022448] rounded">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('master.destroy', ['entitas' => $kunci, 'id' => $row->id]) }}" class="inline" onsubmit="return confirm('Hapus {{ addslashes($row->name) }} dari master?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Data" class="p-1 text-[#515f74] hover:text-[#dc2626] rounded">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-sm text-[#515f74]">Belum ada data {{ strtolower($daftar[$kunci]['label']) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#515f74]">
                <div class="flex items-center gap-2">
                    <span>Menampilkan <strong>{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong></span>
                    <span class="text-[#94a3b8]">|</span>
                    <form method="GET" action="{{ route('master.index') }}" class="flex items-center gap-1.5">
                        <input type="hidden" name="entitas" value="{{ $kunci }}">
                        @foreach (request()->except('per_halaman', 'page', 'entitas') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <label class="flex items-center gap-1.5">Tampilkan
                            <select name="per_halaman" onchange="this.form.submit()" class="h-7 px-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs text-[#0f172a]">
                                @foreach ([10, 25, 50] as $n)
                                    <option value="{{ $n }}" @selected($data->perPage() == $n)>{{ $n }}</option>
                                @endforeach
                            </select> baris
                        </label>
                    </form>
                </div>
                <div class="inline-flex items-center gap-1">
                    @if ($data->onFirstPage())
                        <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed">Sebelumnya</span>
                    @else
                        <a href="{{ $data->previousPageUrl() }}" class="px-2.5 py-1 rounded hover:bg-[#f1f5f9] border border-[#e2e7ff] font-medium">Sebelumnya</a>
                    @endif
                    @foreach ($data->getUrlRange(max(1, $data->currentPage() - 1), min($data->lastPage(), $data->currentPage() + 1)) as $page => $url)
                        @if ($page == $data->currentPage())
                            <span class="px-2.5 py-1 rounded bg-[#022448] text-white font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-2.5 py-1 rounded hover:bg-[#f1f5f9] border border-[#e2e7ff] font-medium">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($data->hasMorePages())
                        <a href="{{ $data->nextPageUrl() }}" class="px-2.5 py-1 rounded hover:bg-[#f1f5f9] border border-[#e2e7ff] font-medium">Berikutnya</a>
                    @else
                        <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed">Berikutnya</span>
                    @endif
                </div>
            </div>
        </div>

        <footer class="pt-5 pb-2 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 text-[#515f74] text-xs">
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

    {{-- Modal tambah/ubah --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#283044]/40 backdrop-blur-sm hidden" id="modalMaster">
        <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 bg-[#f1f5f9]">
                <h3 class="text-base font-bold text-[#0f172a]" id="modalJudul">Tambah {{ $daftar[$kunci]['label'] }}</h3>
                <button class="p-1 rounded-lg text-[#515f74] hover:text-[#0f172a] hover:bg-[#e2e7ff] transition-colors" onclick="tutupModalMaster()" type="button">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('master.store', ['entitas' => $kunci]) }}" id="formMaster" class="p-5 flex flex-col gap-3">
                @csrf
                <div id="methodMaster"></div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#0f172a]" for="masterNama">Nama <span class="text-[#ba1a1a]">*</span></label>
                    <input class="h-10 px-4 bg-[#f1f5f9] rounded-lg text-sm text-[#0f172a] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="masterNama" name="name" value="{{ old('name') }}" required type="text" maxlength="100">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-[#0f172a]" for="masterDeskripsi">Keterangan</label>
                    <textarea class="p-3 bg-[#f1f5f9] rounded-lg text-sm text-[#0f172a] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="masterDeskripsi" name="description" rows="3" maxlength="255">{{ old('description') }}</textarea>
                </div>
                <div class="flex items-center justify-end gap-2 mt-1">
                    <button class="px-5 py-2 text-[#515f74] hover:text-[#0f172a] hover:bg-[#f1f5f9] rounded-lg text-sm transition-colors" onclick="tutupModalMaster()" type="button">Batalkan</button>
                    <button class="px-6 py-2 bg-[#022448] hover:bg-[#1e3a5f] text-white text-sm font-semibold rounded-lg shadow-sm transition-colors" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Buka kembali modal tambah bila validasi gagal (mis. auto-submit
        // ekstensi pengisi form), agar isian tidak hilang sia-sia.
        @if ($errors->any())
            document.getElementById('modalMaster').classList.remove('hidden');
        @endif
        const modalMaster = document.getElementById('modalMaster');
        const formMaster = document.getElementById('formMaster');

        function bukaModalTambah() {
            document.getElementById('modalJudul').textContent = 'Tambah {{ $daftar[$kunci]['label'] }}';
            formMaster.action = '{{ route('master.store', ['entitas' => $kunci]) }}';
            document.getElementById('methodMaster').innerHTML = '';
            document.getElementById('masterNama').value = '';
            document.getElementById('masterDeskripsi').value = '';
            modalMaster.classList.remove('hidden');
        }

        function bukaModalUbah(id, nama, deskripsi) {
            document.getElementById('modalJudul').textContent = 'Ubah {{ $daftar[$kunci]['label'] }}';
            formMaster.action = '{{ url('admin/master/'.$kunci) }}/' + id;
            document.getElementById('methodMaster').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('masterNama').value = nama;
            document.getElementById('masterDeskripsi').value = deskripsi ?? '';
            modalMaster.classList.remove('hidden');
        }

        function tutupModalMaster() {
            modalMaster.classList.add('hidden');
        }
    </script>
</x-layouts::app>
