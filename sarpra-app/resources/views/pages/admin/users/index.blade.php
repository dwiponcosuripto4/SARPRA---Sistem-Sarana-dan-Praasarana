<x-layouts::app :title="__('Manajemen Pengguna')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pengguna" judulHalaman="Pengguna">
    @php
        $badgePeran = [
            'Administrator' => ['kelas' => 'bg-[#d5e3ff] text-[#022448]', 'ikon' => 'shield'],
            'Pimpinan' => ['kelas' => 'bg-[#00422c] text-[#85f8c4]', 'ikon' => 'verified_user'],
            'Pengelola Sarana & Prasarana' => ['kelas' => 'bg-[#d5e3fc] text-[#57657a]', 'ikon' => 'inventory'],
        ];
    @endphp

    {{-- Kartu judul --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl shadow-sm">
        <div class="flex flex-col gap-1 max-w-2xl">
            <div class="flex items-center gap-2">
                <span class="p-1.5 bg-[#d5e3fc] text-[#57657a] rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">manage_accounts</span>
                </span>
                <h1 class="text-[28px] leading-9 font-bold text-[#131b2e] tracking-tight">Manajemen Pengguna</h1>
            </div>
            <p class="text-sm text-[#515f74]">Kelola akun pengguna, penugasan peran akses sistem, dan status kredensial operasional sarana prasarana.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('pengguna.ekspor', request()->query()) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-[#f2f3ff] hover:bg-[#eaedff] text-[#57657a] text-sm font-medium rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-lg">file_download</span>
                <span>Export Data</span>
            </a>
            <button class="inline-flex items-center gap-2 px-5 py-2 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-semibold rounded-lg shadow-sm transition-all" onclick="document.getElementById('modalTambahPengguna').classList.remove('hidden')" type="button">
                <span class="material-symbols-outlined text-lg">person_add</span>
                <span>+ Tambah Pengguna Baru</span>
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
            <div class="flex-1">
                <p class="text-sm font-medium text-[#93000a]">Data belum valid</p>
                <p class="text-xs text-[#93000a]/90 mt-0.5">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    {{-- 4 kartu statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="flex items-center gap-4 p-5 bg-white rounded-xl shadow-sm">
            <div class="w-12 h-12 rounded-lg bg-[#d5e3fc] text-[#1e3a5f] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px]">groups</span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Total Pengguna</span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-[28px] leading-9 font-bold text-[#131b2e]">{{ $totalPengguna }}</span>
                    <span class="text-xs text-[#515f74]">Akun Terdaftar</span>
                </div>
            </div>
        </div>
        @foreach ($perPeran as $i => $peran)
            @php
                $ikonStat = ['shield_person', 'home_repair_service', 'verified_user'][$i % 3];
                $bgStat = ['bg-[#d5e3ff] text-[#022448]', 'bg-[#d5e3fc] text-[#57657a]', 'bg-[#85f8c4] text-[#005137]'][$i % 3];
            @endphp
            <div class="flex items-center gap-4 p-5 bg-white rounded-xl shadow-sm">
                <div class="w-12 h-12 rounded-lg {{ $bgStat }} flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px]">{{ $ikonStat }}</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider truncate">{{ $peran->name }}</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[28px] leading-9 font-bold text-[#131b2e]">{{ $peran->aktif_count }}</span>
                        <span class="text-xs text-[#515f74]">Akun</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('pengguna.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 p-4 bg-white rounded-xl shadow-sm mt-6">
        <div class="relative flex-1 flex items-center">
            <span class="material-symbols-outlined absolute left-4 text-xl text-[#515f74] pointer-events-none">search</span>
            <input class="w-full h-10 pl-11 pr-4 bg-[#f2f3ff] rounded-lg text-[#131b2e] placeholder:text-[#74777f] text-sm focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#1e3a5f]/20 transition-all" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email..." type="text">
        </div>
        <div class="relative w-full sm:w-56">
            <select class="w-full h-10 pl-4 pr-9 bg-[#f2f3ff] text-[#131b2e] text-sm rounded-lg appearance-none cursor-pointer focus:outline-none" name="role" onchange="this.form.submit()">
                <option value="">Semua Peran</option>
                @foreach ($roles as $r)
                    <option value="{{ $r->name }}" @selected(request('role') === $r->name)>{{ $r->name }}</option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-xl text-[#515f74] pointer-events-none">expand_more</span>
        </div>
        <div class="relative w-full sm:w-48">
            <select class="w-full h-10 pl-4 pr-9 bg-[#f2f3ff] text-[#131b2e] text-sm rounded-lg appearance-none cursor-pointer focus:outline-none" name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
            </select>
            <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-xl text-[#515f74] pointer-events-none">expand_more</span>
        </div>
        <a class="inline-flex items-center justify-center gap-1.5 px-4 h-10 text-[#515f74] hover:text-[#131b2e] hover:bg-[#f2f3ff] rounded-lg text-xs font-semibold transition-colors" href="{{ route('pengguna.index') }}">
            <span class="material-symbols-outlined text-lg">restart_alt</span>
            <span>Reset Filter</span>
        </a>
        <div class="hidden items-center justify-end gap-3 px-4 py-1.5 bg-[#d5e3fc] rounded-lg" id="batchSelectionBar">
            <span class="text-xs text-[#57657a] font-semibold" id="selectedCountText">0 terpilih</span>
            <button class="px-2.5 py-1 bg-white text-[#131b2e] hover:bg-[#eaedff] text-xs rounded shadow-sm" type="button" onclick="kirimBatch('aktifkan')">Aktifkan</button>
            <button class="px-2.5 py-1 bg-[#ba1a1a] text-white text-xs rounded shadow-sm" type="button" onclick="kirimBatch('nonaktifkan')">Nonaktifkan</button>
        </div>
    </form>

    <form id="formBatch" method="POST" action="{{ route('pengguna.batch') }}">
        @csrf
        <input type="hidden" name="aksi" id="batchAksi" value="">
    </form>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col mt-6">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f2f3ff] text-[#515f74] text-xs font-semibold tracking-wider uppercase">
                        <th class="py-3 px-5 w-12 text-center"><input class="w-4 h-4 rounded accent-[#1e3a5f] cursor-pointer" id="selectAllCheckbox" onchange="toggleSelectAll(this)" type="checkbox"></th>
                        <th class="py-3 px-5 min-w-[240px]">Nama Lengkap &amp; Identitas</th>
                        <th class="py-3 px-5 min-w-[200px]">Email / Username</th>
                        <th class="py-3 px-5 min-w-[190px]">Peran / Role</th>
                        <th class="py-3 px-5 min-w-[130px]">Status Akun</th>
                        <th class="py-3 px-5 min-w-[140px]">Tanggal Dibuat</th>
                        <th class="py-3 px-5 w-28 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-0 text-[#131b2e] text-sm">
                    @forelse ($pengguna as $u)
                        @php $badge = $badgePeran[$u->role?->name] ?? ['kelas' => 'bg-[#f2f3ff] text-[#515f74]', 'ikon' => 'badge']; @endphp
                        <tr class="hover:bg-[#f2f3ff]/60 transition-colors group {{ $u->is_active ? '' : 'opacity-85' }}">
                            <td class="py-3 px-5 text-center">
                                <input class="row-checkbox w-4 h-4 rounded accent-[#1e3a5f] cursor-pointer" onchange="updateSelectedCount()" type="checkbox" value="{{ $u->id }}">
                            </td>
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $u->is_active ? 'bg-[#d5e3fc] text-[#57657a]' : 'bg-[#e2e7ff] text-[#515f74]' }} font-bold flex items-center justify-center text-xs shrink-0">{{ $u->initials() }}</div>
                                    <div class="flex flex-col min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm font-semibold truncate">{{ $u->name }}</span>
                                            @unless ($u->is_active)
                                                <span class="material-symbols-outlined text-sm text-[#ba1a1a]" title="Akses dibekukan">lock</span>
                                            @endunless
                                        </div>
                                        <span class="text-xs text-[#515f74] leading-tight" style="font-family: 'JetBrains Mono', monospace;">ID: USR-{{ str_pad($u->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-5">
                                <div class="flex flex-col">
                                    <span class="text-[13px]" style="font-family: 'JetBrains Mono', monospace;">{{ $u->email }}</span>
                                    <span class="text-xs text-[#515f74]">@{{ \Illuminate\Support\Str::before($u->email, '@') }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-semibold {{ $badge['kelas'] }}">
                                    <span class="material-symbols-outlined text-sm">{{ $badge['ikon'] }}</span>
                                    {{ $u->role?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-5">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#00422c]/15 text-[#00422c] text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#3fb687]"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#ffdad6] text-[#ba1a1a] text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#ba1a1a]"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-[#515f74] text-xs" style="font-family: 'JetBrains Mono', monospace;">
                                {{ $u->created_at?->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="p-1.5 text-[#515f74] hover:text-[#022448] hover:bg-[#eaedff] rounded-lg transition-colors" title="Ubah Profil Pengguna" type="button">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="p-1.5 text-[#515f74] hover:text-[#022448] hover:bg-[#eaedff] rounded-lg transition-colors" title="Kirim Reset Password" type="button">
                                        <span class="material-symbols-outlined text-lg">key</span>
                                    </button>
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('pengguna.toggle', $u) }}" class="inline">
                                            @csrf
                                            @if ($u->is_active)
                                                <button class="p-1.5 text-[#515f74] hover:text-[#ba1a1a] hover:bg-[#ffdad6]/40 rounded-lg transition-colors" title="Nonaktifkan Akun" type="submit">
                                                    <span class="material-symbols-outlined text-lg">person_off</span>
                                                </button>
                                            @else
                                                <button class="p-1.5 text-[#00422c] hover:bg-[#00422c]/20 rounded-lg transition-colors" title="Aktifkan Akun Kembali" type="submit">
                                                    <span class="material-symbols-outlined text-lg">check_circle</span>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 bg-[#f2f3ff] text-[#515f74] text-sm">
            <div class="flex items-center gap-4">
                <span>Menampilkan <span class="font-semibold text-[#131b2e]">{{ $pengguna->firstItem() ?? 0 }} - {{ $pengguna->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-[#131b2e]">{{ $pengguna->total() }}</span> data</span>
                <form method="GET" action="{{ route('pengguna.index') }}" class="flex items-center gap-1.5">
                    @foreach (request()->except('per_halaman', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <span>Baris:</span>
                    <select name="per_halaman" onchange="this.form.submit()" class="h-8 px-1 bg-white text-[#131b2e] rounded text-xs focus:outline-none cursor-pointer" style="font-family: 'JetBrains Mono', monospace;">
                        @foreach ([10, 25, 50] as $n)
                            <option value="{{ $n }}" @selected($pengguna->perPage() == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-1 text-sm">
                @if ($pengguna->onFirstPage())
                    <span class="px-3 py-1 text-[#74777f] flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">chevron_left</span>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $pengguna->previousPageUrl() }}" class="px-3 py-1 hover:bg-[#eaedff] rounded-lg flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-base">chevron_left</span>
                        <span>Sebelumnya</span>
                    </a>
                @endif
                @foreach ($pengguna->getUrlRange(max(1, min($pengguna->currentPage() - 1, $pengguna->lastPage() - 2)), min($pengguna->lastPage(), max(3, $pengguna->currentPage() + 1))) as $page => $url)
                    @if ($page == $pengguna->currentPage())
                        <span class="w-8 h-8 rounded-lg bg-[#1e3a5f] text-white font-bold shadow-sm flex items-center justify-center">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg hover:bg-[#eaedff] transition-colors flex items-center justify-center">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($pengguna->lastPage() > 3 && $pengguna->currentPage() < $pengguna->lastPage() - 1)
                    <span class="px-1 text-xs" style="font-family: 'JetBrains Mono', monospace;">...</span>
                    <a href="{{ $pengguna->url($pengguna->lastPage()) }}" class="w-8 h-8 rounded-lg hover:bg-[#eaedff] transition-colors flex items-center justify-center">{{ $pengguna->lastPage() }}</a>
                @endif
                @if ($pengguna->hasMorePages())
                    <a href="{{ $pengguna->nextPageUrl() }}" class="px-3 py-1 hover:bg-[#eaedff] rounded-lg flex items-center gap-1 transition-colors">
                        <span>Berikutnya</span>
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </a>
                @else
                    <span class="px-3 py-1 text-[#74777f] flex items-center gap-1">
                        <span>Berikutnya</span>
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal tambah pengguna --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#283044]/40 backdrop-blur-sm hidden" id="modalTambahPengguna">
        <div class="relative w-full max-w-xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 bg-[#f2f3ff] sticky top-0">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-[#1e3a5f] text-white flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl">person_add</span>
                    </span>
                    <div class="flex flex-col">
                        <h2 class="text-base font-bold text-[#131b2e]">Registrasi Pengguna Baru</h2>
                        <span class="text-xs text-[#515f74]">Kredensial dan penugasan akses modul SARPRA</span>
                    </div>
                </div>
                <button class="p-1 rounded-lg text-[#515f74] hover:text-[#131b2e] hover:bg-[#eaedff] transition-colors" onclick="document.getElementById('modalTambahPengguna').classList.add('hidden')" type="button">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('pengguna.store') }}" class="p-6 flex flex-col gap-3">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#131b2e]" for="nama">Nama Lengkap</label>
                        <input class="h-10 px-4 bg-[#f2f3ff] rounded-lg text-sm text-[#131b2e] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="nama" name="name" value="{{ old('name') }}" placeholder="Contoh: Andi Pratama" required type="text">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#131b2e]" for="email">Email Dinas</label>
                        <input class="h-10 px-4 bg-[#f2f3ff] rounded-lg text-sm text-[#131b2e] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="email" name="email" value="{{ old('email') }}" placeholder="nama@sarpra.go.id" required type="email">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#131b2e]" for="password">Kata Sandi</label>
                        <input class="h-10 px-4 bg-[#f2f3ff] rounded-lg text-sm text-[#131b2e] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="password" name="password" placeholder="Minimal 8 karakter" required type="password">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#131b2e]" for="password_confirmation">Konfirmasi Sandi</label>
                        <input class="h-10 px-4 bg-[#f2f3ff] rounded-lg text-sm text-[#131b2e] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="password_confirmation" name="password_confirmation" required type="password">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-[#131b2e]" for="role_id">Peran Akses (Role)</label>
                        <select class="h-10 px-4 bg-[#f2f3ff] rounded-lg text-sm text-[#131b2e] focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]" id="role_id" name="role_id" required>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id }}" @selected(old('role_id') == $r->id)>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input class="w-4 h-4 rounded accent-[#1e3a5f] cursor-pointer" name="is_active" type="checkbox" value="1" @checked(old('is_active', true))>
                            <span class="text-sm text-[#515f74]">Langsung aktifkan akun</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 bg-[#d5e3fc]/60 rounded-lg text-[#57657a] text-xs">
                    <span class="material-symbols-outlined text-lg text-[#022448] shrink-0">info</span>
                    <span>Akun dibuat dengan kata sandi di atas. Sampaikan kredensial kepada pengguna terkait.</span>
                </div>
                <div class="flex items-center justify-end gap-2 mt-1">
                    <button class="px-5 py-2 text-[#515f74] hover:text-[#131b2e] hover:bg-[#f2f3ff] rounded-lg text-sm transition-colors" onclick="document.getElementById('modalTambahPengguna').classList.add('hidden')" type="button">Batalkan</button>
                    <button class="px-6 py-2 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-semibold rounded-lg shadow-sm transition-colors" type="submit">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSelectAll(master) {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const boxes = document.querySelectorAll('.row-checkbox');
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            const bar = document.getElementById('batchSelectionBar');
            document.getElementById('selectedCountText').innerText = checked + ' terpilih';
            bar.classList.toggle('hidden', checked === 0);
            bar.classList.toggle('flex', checked > 0);
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) selectAll.checked = checked === boxes.length && boxes.length > 0;
        }

        function kirimBatch(aksi) {
            const form = document.getElementById('formBatch');
            document.getElementById('batchAksi').value = aksi;
            form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'ids[]';
                hidden.value = cb.value;
                form.appendChild(hidden);
            });
            form.submit();
        }
    </script>
</x-layouts::app>
