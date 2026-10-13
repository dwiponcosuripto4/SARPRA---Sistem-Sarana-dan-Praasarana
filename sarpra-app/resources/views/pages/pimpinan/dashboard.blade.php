<x-layouts::app :title="__('Dashboard Pimpinan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="dashboard">
    @php
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
        $pctRealisasi = $pagu > 0 ? round($realisasi / $pagu * 100, 1) : 0;
        $pctBaik = $totalUnit > 0 ? round($baik / $totalUnit * 100, 1) : 0;
        $badgePrioritas = [
            'Tinggi' => 'bg-rose-100 text-rose-700 border-rose-200',
            'Sedang' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Rendah' => 'bg-slate-100 text-slate-700 border-slate-200',
            'Mendesak' => 'bg-red-100 text-red-800 border-red-300',
        ];
    @endphp

    {{-- Judul --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-1">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl font-extrabold text-[#0f172a] tracking-tight leading-tight">Dashboard Eksekutif &amp; Pengawasan</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#022448]/10 text-[#022448] border border-[#022448]/20">Portal Pimpinan</span>
            </div>
            <p class="text-sm text-[#515f74] mt-1">Pemantauan aset strategis, realisasi anggaran pengadaan, dan otorisasi persetujuan terpadu.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-[#e2e7ff] rounded-lg shadow-xs text-xs font-semibold text-[#515f74]">
                <span class="material-symbols-outlined text-base text-[#022448]">calendar_today</span>
                <span>T.A. {{ now()->locale('id')->translatedFormat('Y') }}/{{ now()->addYear()->locale('id')->translatedFormat('Y') }}</span>
            </div>
            <a href="{{ route('dashboard.ekspor-eksekutif') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#022448] text-white hover:bg-[#1e3a5f] rounded-lg text-xs font-semibold shadow-xs transition-colors">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                <span>Ekspor Laporan Eksekutif</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ecfdf5] border border-emerald-200 rounded-xl">
            <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0">check_circle</span>
            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    {{-- Kartu metrik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col justify-between hover:border-[#8aa4cf] transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#515f74]">Total Keseluruhan Aset</span>
                    <h3 class="text-2xl font-extrabold text-[#0f172a] mt-1.5 tracking-tight">{{ number_format($totalUnit, 0, ',', '.') }} <span class="text-sm font-semibold text-[#515f74]">Unit</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#022448] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">devices_other</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[#f1f5f9] flex items-center justify-between text-xs">
                <span class="text-[#515f74] font-medium">Valuasi Est. <strong class="text-[#0f172a] font-semibold">{{ $rupiah($valuasi) }}</strong></span>
            </div>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col justify-between hover:border-[#8aa4cf] transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#515f74]">Realisasi Anggaran</span>
                    <h3 class="text-2xl font-extrabold text-[#0f172a] mt-1.5 tracking-tight">{{ $rupiah($realisasi) }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[#f1f5f9] flex flex-col gap-1.5">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-[#515f74]">Pagu: {{ $rupiah($pagu) }}</span>
                    <span class="font-bold text-[#022448]">{{ number_format($pctRealisasi, 1, ',', '.') }}%</span>
                </div>
                <div class="w-full bg-[#f1f5f9] h-1.5 rounded-full overflow-hidden">
                    <div class="bg-[#022448] h-full rounded-full" style="width: {{ min(100, $pctRealisasi) }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col justify-between hover:border-[#8aa4cf] transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold uppercase tracking-wider text-[#515f74]">Menunggu Otorisasi</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-[#0f172a] mt-1.5 tracking-tight">{{ $totalAntrean }} <span class="text-sm font-semibold text-amber-800">Usulan</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 border border-amber-200 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">notification_important</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[#f1f5f9] flex items-center justify-between text-xs">
                <span class="text-[#515f74] font-medium">Total butuh respon:</span>
                <span class="font-bold text-amber-950" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiah($totalButuhRespon) }}</span>
            </div>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col justify-between hover:border-[#8aa4cf] transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#515f74]">Kondisi &amp; Kelaikan Aset</span>
                    <div class="flex items-baseline gap-2 mt-1.5">
                        <h3 class="text-2xl font-extrabold text-emerald-700 tracking-tight">{{ number_format($pctBaik, 1, ',', '.') }}%</h3>
                        <span class="text-xs font-semibold text-[#515f74]">Kondisi Baik</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">verified</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-[#f1f5f9] flex flex-col gap-2">
                <div class="flex h-2 w-full rounded-full overflow-hidden bg-slate-200 gap-0.5">
                    <div class="bg-emerald-500 h-full" style="width: {{ $pctBaik }}%"></div>
                    <div class="bg-amber-400 h-full" style="width: {{ $totalUnit > 0 ? round($ringan / $totalUnit * 100, 1) : 0 }}%"></div>
                    <div class="bg-rose-500 h-full" style="width: {{ $totalUnit > 0 ? round($berat / $totalUnit * 100, 1) : 0 }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-[#515f74] font-medium">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Baik ({{ number_format($baik, 0, ',', '.') }})</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> R. Ringan ({{ number_format($ringan, 0, ',', '.') }})</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> R. Berat ({{ number_format($berat, 0, ',', '.') }})</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Antrean persetujuan --}}
    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-[#e2e7ff] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-100 text-amber-800 rounded-lg shrink-0">
                    <span class="material-symbols-outlined text-xl">gavel</span>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-base font-bold text-[#0f172a]">Pengajuan Pengadaan Menunggu Persetujuan</h2>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-500 text-white">{{ $totalAntrean }} Menunggu</span>
                    </div>
                    <p class="text-xs text-[#515f74] mt-0.5">Daftar proposal pengadaan yang memerlukan otorisasi Pejabat Pembuat Komitmen (PPK) / Pimpinan.</p>
                </div>
            </div>
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 text-xs shrink-0">
                <span class="text-[#515f74] font-medium">Filter Prioritas:</span>
                <select name="prioritas" onchange="this.form.submit()" class="bg-white border border-[#e2e7ff] rounded-lg px-2.5 py-1.5 text-xs text-[#0f172a] font-medium focus:outline-none focus:border-[#022448]">
                    <option value="">Semua Prioritas</option>
                    @foreach (['Mendesak', 'Tinggi', 'Sedang', 'Rendah'] as $pr)
                        <option value="{{ $pr }}" @selected(request('prioritas') === $pr)>{{ $pr }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#f1f5f9]/70 border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Tanggal &amp; No. Usulan</th>
                        <th class="py-3 px-4">Pengaju &amp; Unit Kerja</th>
                        <th class="py-3 px-4">Nama Paket &amp; Lingkup</th>
                        <th class="py-3 px-4 text-right">Total Estimasi Biaya</th>
                        <th class="py-3 px-4 text-center">Prioritas</th>
                        <th class="py-3 px-4 text-center w-52">Aksi Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($antrean as $i => $p)
                        @php
                            $namaPrioritas = $p->perencanaan?->prioritas?->name ?? '-';
                            $total = $p->detail->sum('subtotal');
                            $paket = $p->detail->first();
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="py-3.5 px-4 text-center text-[#515f74] font-medium" style="font-family: 'JetBrains Mono', monospace;">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $p->nomor_pengadaan }}</div>
                                <div class="text-[#515f74] text-[11px] mt-0.5">{{ $p->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold">{{ $p->user?->name ?? '-' }}</div>
                                <div class="text-[#515f74] text-[11px] mt-0.5">{{ $p->user?->role?->name ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <div class="font-bold group-hover:text-[#022448] transition-colors">{{ $paket?->nama_barang ?? '-' }}{{ $p->detail->count() > 1 ? ' (+'.($p->detail->count() - 1).' item)' : '' }}</div>
                                <div class="text-[#515f74] text-[11px] mt-0.5 truncate">{{ \Illuminate\Support\Str::limit($p->alasan, 70) }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="font-extrabold text-sm" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiah($total) }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgePrioritas[$namaPrioritas] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ $namaPrioritas }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <form method="POST" action="{{ route('persetujuan.setujui', $p) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors shadow-xs" title="Setujui Proposal">
                                            <span class="material-symbols-outlined text-base">check_circle</span>
                                            <span>Setujui</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('persetujuan.tolak', $p) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-xs transition-colors" title="Tolak / Kembalikan">
                                            <span class="material-symbols-outlined text-base">cancel</span>
                                            <span>Tolak</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-4 text-center text-sm text-[#515f74]">Tidak ada usulan menunggu keputusan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3.5 border-t border-[#e2e7ff] bg-[#f1f5f9]/40 flex items-center justify-between text-xs text-[#515f74]">
            <div>Menampilkan <strong>{{ $antrean->count() }}</strong> dari <strong>{{ $totalAntrean }}</strong> usulan menunggu keputusan</div>
            <a href="#" class="inline-flex items-center gap-1 font-semibold text-[#022448] hover:underline">
                <span>Buka Antrean Lengkap Pengadaan</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
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
</x-layouts::app>
