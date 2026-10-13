<x-layouts::app :title="__('Data Master')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="master" judulHalaman="Data Master">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Data Master</h1>
            <p class="text-sm text-[#515f74]">Kamus data referensi seluruh modul SARPRA.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mt-6">
        @foreach ($daftar as $key => $entitas)
            <a href="{{ route('master.index', ['entitas' => $key]) }}" class="px-3.5 py-2 rounded-lg text-xs font-semibold border transition-colors {{ $kunci === $key ? 'bg-[#022448] text-white border-[#022448] shadow-xs' : 'bg-white text-[#515f74] border-[#e2e7ff] hover:border-[#1e3a5f] hover:text-[#022448]' }}">
                {{ $entitas['label'] }}
            </a>
        @endforeach
    </div>

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-4">
        <form method="GET" action="{{ route('master.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-[#0f172a]">{{ $daftar[$kunci]['label'] }}</h2>
                <p class="text-xs text-[#515f74] mt-0.5">{{ $data->total() }} data</p>
            </div>
            <div class="flex items-center gap-2.5">
                <input type="hidden" name="entitas" value="{{ $kunci }}">
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama..." class="w-60 h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Nama</th>
                        <th class="py-3.5 px-5">Deskripsi</th>
                        <th class="py-3.5 px-5">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($data as $row)
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5 font-semibold">{{ $row->name }}</td>
                            <td class="py-3.5 px-5 text-[#515f74]">{{ $row->description ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $row->created_at?->locale('id')->translatedFormat('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data.</td>
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
