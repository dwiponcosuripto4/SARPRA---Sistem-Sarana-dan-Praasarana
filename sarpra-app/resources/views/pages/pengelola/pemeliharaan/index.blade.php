<x-layouts::app :title="__('Pemeliharaan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pemeliharaan" judulHalaman="Pemeliharaan">
    @php
        $badgeStatus = [
            'diajukan' => 'bg-blue-50 text-blue-700 border-blue-200',
            'dalam_pemeliharaan' => 'bg-amber-50 text-amber-800 border-amber-200',
            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
        ];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Pemeliharaan</h1>
            <p class="text-sm text-[#515f74]">Tiket servis dan perbaikan sarana prasarana.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('pemeliharaan.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-wrap items-center gap-2.5">
            <div class="relative">
                <select name="status" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer appearance-none">
                    <option value="">Semua Status</option>
                    <option value="diajukan" @selected(request('status') === 'diajukan')>Diajukan</option>
                    <option value="dalam_pemeliharaan" @selected(request('status') === 'dalam_pemeliharaan')>Dalam Pemeliharaan</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Barang</th>
                        <th class="py-3.5 px-5">Jenis</th>
                        <th class="py-3.5 px-5">Tanggal</th>
                        <th class="py-3.5 px-5">Masalah</th>
                        <th class="py-3.5 px-5">Estimasi</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Pengaju</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($data as $row)
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="font-semibold">{{ $row->barang?->nama_barang ?? '-' }}</div>
                                <div class="text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $row->barang?->kode_barang ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $row->jenis?->name ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">{{ $row->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-5">{{ \Illuminate\Support\Str::limit($row->masalah, 70) }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">Rp {{ number_format($row->estimasi_biaya, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeStatus[$row->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ str_replace('_', ' ', ucfirst($row->status)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $row->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data pemeliharaan.</td>
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
