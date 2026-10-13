<x-layouts::app :title="__('Manajemen Pengguna')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pengguna" judulHalaman="Pengguna">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Manajemen Pengguna</h1>
            <p class="text-sm text-[#515f74]">Kelola akun, peran, dan status aktif pengguna SARPRA.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    @if (session('status'))
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ecfdf5] border border-emerald-200 rounded-xl">
            <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0">check_circle</span>
            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('pengguna.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-[#0f172a]">Daftar Pengguna</h2>
                <p class="text-xs text-[#515f74] mt-0.5">{{ $pengguna->total() }} akun terdaftar</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / email..." class="w-60 h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
                </div>
                <div class="relative">
                    <select name="role" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer appearance-none">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}" @selected(request('role') === $r->name)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Role</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Login Terakhir</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($pengguna as $u)
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#1e3a5f] text-white font-bold flex items-center justify-center text-xs shrink-0">{{ $u->initials() }}</div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ $u->name }}</span>
                                        <span class="text-[11px] text-[#515f74]">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc] text-[#57657a] text-[10px] font-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $u->role?->name ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $u->last_login_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-right">
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('pengguna.toggle', $u) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-[#e2e7ff] text-xs font-semibold {{ $u->is_active ? 'text-rose-600 hover:bg-rose-50' : 'text-emerald-700 hover:bg-emerald-50' }} transition-colors">
                                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-[#94a3b8]">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#f1f5f9]/40">
            <span class="text-xs text-[#515f74]">Menampilkan <strong class="text-[#0f172a]">{{ $pengguna->firstItem() ?? 0 }} - {{ $pengguna->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a]">{{ $pengguna->total() }}</strong> pengguna</span>
            <div class="flex items-center gap-1">
                @if ($pengguna->onFirstPage())
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#94a3b8] bg-[#f1f5f9]">Sebelumnya</span>
                @else
                    <a href="{{ $pengguna->previousPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] hover:bg-[#f1f5f9]">Sebelumnya</a>
                @endif
                @foreach ($pengguna->getUrlRange(max(1, $pengguna->currentPage() - 1), min($pengguna->lastPage(), $pengguna->currentPage() + 1)) as $page => $url)
                    @if ($page == $pengguna->currentPage())
                        <span class="w-8 h-8 rounded-lg bg-[#022448] text-white text-xs font-semibold flex items-center justify-center">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg border border-[#e2e7ff] text-[#515f74] hover:bg-[#f1f5f9] text-xs flex items-center justify-center">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($pengguna->hasMorePages())
                    <a href="{{ $pengguna->nextPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] hover:bg-[#f1f5f9]">Selanjutnya</a>
                @else
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#94a3b8] bg-[#f1f5f9]">Selanjutnya</span>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
