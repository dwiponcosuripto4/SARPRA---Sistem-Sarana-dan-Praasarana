<x-layouts::app :title="__('Perencanaan Kebutuhan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="perencanaan" judulHalaman="Perencanaan">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Perencanaan Kebutuhan</h1>
            <p class="text-sm text-[#515f74]">Daftar kebutuhan sarana dan prasarana per periode.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('perencanaan.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-wrap items-center gap-2.5">
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kebutuhan..." class="w-60 h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
            </div>
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer appearance-none">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="diajukan" @selected(request('status') === 'diajukan')>Diajukan</option>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Kebutuhan</th>
                        <th class="py-3.5 px-5">Kategori</th>
                        <th class="py-3.5 px-5">Jumlah</th>
                        <th class="py-3.5 px-5">Prioritas</th>
                        <th class="py-3.5 px-5">Periode</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Pengusul</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($data as $row)
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="font-semibold">{{ $row->nama_kebutuhan }}</div>
                                <div class="text-[11px] text-[#515f74]">{{ \Illuminate\Support\Str::limit($row->alasan, 60) }}</div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $row->kategori?->name ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">{{ $row->jumlah }} {{ $row->satuan?->name ?? '' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc] text-[#57657a] text-[10px] font-semibold">{{ $row->prioritas?->name ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-[#515f74]">{{ $row->periode?->name ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                @if ($row->status === 'diajukan')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Diajukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $row->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data perencanaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#e2e7ff] flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#f1f5f9]/40">
            <span class="text-xs text-[#515f74]">Menampilkan <strong class="text-[#0f172a]">{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</strong> dari <strong class="text-[#0f172a]">{{ $data->total() }}</strong> data</span>
            <div class="flex items-center gap-1">
                @if ($data->onFirstPage())
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#94a3b8] bg-[#f1f5f9]">Sebelumnya</span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] hover:bg-[#f1f5f9]">Sebelumnya</a>
                @endif
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] hover:bg-[#f1f5f9]">Selanjutnya</a>
                @else
                    <span class="px-2.5 py-1.5 border border-[#e2e7ff] rounded-lg text-xs text-[#94a3b8] bg-[#f1f5f9]">Selanjutnya</span>
                @endif
            </div>
        </div>
    </div>
</x-layouts::app>
