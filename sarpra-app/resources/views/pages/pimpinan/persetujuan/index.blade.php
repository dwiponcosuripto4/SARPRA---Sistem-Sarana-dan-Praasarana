<x-layouts::app :title="__('Pengadaan & Persetujuan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="persetujuan" judulHalaman="Persetujuan">
    @php
        $badgePrioritas = [
            'Tinggi' => 'bg-rose-100 text-rose-700 border-rose-200',
            'Sedang' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Rendah' => 'bg-slate-100 text-slate-700 border-slate-200',
            'Mendesak' => 'bg-red-100 text-red-800 border-red-300',
        ];
        $badgeStatus = [
            'diajukan' => 'bg-blue-50 text-blue-700 border-blue-200',
            'menunggu_persetujuan' => 'bg-amber-50 text-amber-800 border-amber-200',
            'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
        ];
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Pengadaan &amp; Persetujuan</h1>
            <p class="text-sm text-[#515f74]">Otorisasi proposal pengadaan oleh pimpinan.</p>
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

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden mt-6">
        <form method="GET" action="{{ route('persetujuan.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-wrap items-center gap-2.5">
            <div class="relative">
                <select name="prioritas" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer appearance-none">
                    <option value="">Semua Prioritas</option>
                    @foreach (['Mendesak', 'Tinggi', 'Sedang', 'Rendah'] as $pr)
                        <option value="{{ $pr }}" @selected(request('prioritas') === $pr)>{{ $pr }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Nomor</th>
                        <th class="py-3.5 px-5">Pengaju</th>
                        <th class="py-3.5 px-5">Paket</th>
                        <th class="py-3.5 px-5 text-right">Estimasi</th>
                        <th class="py-3.5 px-5 text-center">Prioritas</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-center">Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($data as $p)
                        @php
                            $namaPrioritas = $p->perencanaan?->prioritas?->name ?? '-';
                            $total = $p->detail->sum('subtotal');
                            $paket = $p->detail->first();
                        @endphp
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $p->nomor_pengadaan }}</div>
                                <div class="text-[11px] text-[#515f74]">{{ $p->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $p->user?->name ?? '-' }}</td>
                            <td class="py-3.5 px-5">
                                <div class="font-semibold">{{ $paket?->nama_barang ?? '-' }}</div>
                                <div class="text-[11px] text-[#515f74]">{{ $p->detail->count() }} item</div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap text-right font-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiah($total) }}</td>
                            <td class="py-3.5 px-5 text-center whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgePrioritas[$namaPrioritas] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ $namaPrioritas }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-center whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeStatus[$p->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">{{ str_replace('_', ' ', ucfirst($p->status)) }}</span>
                            </td>
                            <td class="py-3.5 px-5 text-center whitespace-nowrap">
                                @if (in_array($p->status, ['diajukan', 'menunggu_persetujuan'], true))
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form method="POST" action="{{ route('persetujuan.setujui', $p) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                                <span class="material-symbols-outlined text-base">check_circle</span>
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('persetujuan.tolak', $p) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-xs transition-colors">
                                                <span class="material-symbols-outlined text-base">cancel</span>
                                                <span>Tolak</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[11px] text-[#94a3b8]">Sudah diputus ({{ $p->persetujuan?->status ?? $p->status }})</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data pengadaan.</td>
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
