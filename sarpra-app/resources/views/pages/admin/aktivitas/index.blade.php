<x-layouts::app :title="__('Riwayat Aktivitas')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="aktivitas" judulHalaman="Aktivitas">
    @php
        $labelAktivitas = [
            'login' => 'Autentikasi', 'tambah' => 'Tambah Data', 'ubah' => 'Ubah Data',
            'hapus' => 'Hapus Data', 'ajukan' => 'Pengajuan', 'setujui' => 'Persetujuan',
            'tolak' => 'Penolakan', 'ubah_status' => 'Perubahan Status',
        ];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Riwayat Aktivitas</h1>
            <p class="text-sm text-[#515f74]">Log audit aktivitas seluruh pengguna SARPRA.</p>
        </div>
        <a href="{{ route('dashboard.ekspor', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors shadow-xs self-start sm:self-auto">
            <span class="material-symbols-outlined text-base">file_download</span>
            <span>Ekspor</span>
        </a>
    </div>

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('aktivitas.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-wrap items-center gap-2.5">
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari pengguna / aksi..." class="w-60 h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
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
            <div class="relative">
                <select name="aktivitas" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer appearance-none">
                    <option value="">Semua Aktivitas</option>
                    @foreach ($labelAktivitas as $key => $label)
                        <option value="{{ $key }}" @selected(request('aktivitas') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Waktu</th>
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Role</th>
                        <th class="py-3.5 px-5">Aktivitas</th>
                        <th class="py-3.5 px-5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($aktivitas as $a)
                        @php $st = $a->statusTampilan(); @endphp
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5 whitespace-nowrap text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $a->created_at?->locale('id')->translatedFormat('d M Y, H:i:s') }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-semibold">{{ $a->user?->name ?? 'Sistem' }}</span>
                                    <span class="text-[11px] text-[#515f74]">{{ $a->user?->email ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc] text-[#57657a] text-[10px] font-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $a->user?->role?->name ?? 'Sistem' }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $labelAktivitas[$a->activity] ?? $a->activity }}</span>
                                    <span class="text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $a->description }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $st['kelas'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span> {{ $st['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#f1f5f9]/40">
            <span class="text-xs text-[#515f74]">Menampilkan <strong class="text-[#0f172a]">{{ $aktivitas->firstItem() ?? 0 }} - {{ $aktivitas->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a]">{{ $aktivitas->total() }}</strong> data</span>
            <div class="flex items-center gap-1">
                @if ($aktivitas->onFirstPage())
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#94a3b8] bg-[#f1f5f9]">Sebelumnya</span>
                @else
                    <a href="{{ $aktivitas->previousPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] hover:bg-[#f1f5f9]">Sebelumnya</a>
                @endif
                @if ($aktivitas->hasMorePages())
                    <a href="{{ $aktivitas->nextPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] hover:bg-[#f1f5f9]">Selanjutnya</a>
                @else
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#94a3b8] bg-[#f1f5f9]">Selanjutnya</span>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
