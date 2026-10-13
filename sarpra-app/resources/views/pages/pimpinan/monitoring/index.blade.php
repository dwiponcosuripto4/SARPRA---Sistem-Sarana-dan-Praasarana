<x-layouts::app :title="__('Monitoring Aset')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="monitoring" judulHalaman="Monitoring">
    @php
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
        $pctBaik = $totalUnit > 0 ? round($baik / $totalUnit * 100, 1) : 0;
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Monitoring Aset</h1>
            <p class="text-sm text-[#515f74]">Pantauan jumlah, valuasi, dan kondisi aset strategis.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Total Unit</span>
            <p class="text-3xl font-extrabold text-[#0f172a] mt-1">{{ number_format($totalUnit, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Valuasi Aset</span>
            <p class="text-2xl font-extrabold text-[#0f172a] mt-1">{{ $rupiah($valuasi) }}</p>
        </div>
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Kondisi Baik</span>
            <p class="text-3xl font-extrabold text-emerald-700 mt-1">{{ number_format($pctBaik, 1, ',', '.') }}%</p>
            <p class="text-xs text-[#515f74] mt-1">{{ number_format($baik, 0, ',', '.') }} unit</p>
        </div>
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs">
            <span class="text-xs font-medium text-[#515f74] uppercase tracking-wider">Perlu Perhatian</span>
            <p class="text-3xl font-extrabold text-amber-700 mt-1">{{ number_format($ringan + $berat, 0, ',', '.') }}</p>
            <p class="text-xs text-[#515f74] mt-1">Rusak ringan + berat (unit)</p>
        </div>
    </div>

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden mt-6">
        <div class="p-5 border-b border-[#e2e7ff]">
            <h2 class="text-base font-bold text-[#0f172a]">Sebaran Aset per Kategori</h2>
            <p class="text-xs text-[#515f74] mt-0.5">Jumlah unit per kategori barang</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Kategori</th>
                        <th class="py-3.5 px-5">Total Unit</th>
                        <th class="py-3.5 px-5">Proporsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($perKategori as $kat)
                        @php $pct = $totalUnit > 0 ? round($kat->total_unit / $totalUnit * 100, 1) : 0; @endphp
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5 font-semibold">{{ $kat->name }}</td>
                            <td class="py-3.5 px-5" style="font-family: 'JetBrains Mono', monospace;">{{ number_format($kat->total_unit, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-40 bg-[#f1f5f9] h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-[#022448] h-full rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                                    </div>
                                    <span class="text-[11px] text-[#515f74]">{{ number_format($pct, 1, ',', '.') }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
