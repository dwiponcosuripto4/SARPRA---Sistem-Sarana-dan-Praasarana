<x-layouts::app :title="__('Persetujuan Pengadaan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="persetujuan" judulHalaman="Persetujuan">
    @php
        $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
        $rupiahJt = fn ($n) => $n >= 1000000
            ? 'Rp '.number_format($n / 1000000, 1, ',', '.').' Jt'
            : $rupiah($n);
        $badgePrioritas = [
            'Tinggi' => 'bg-rose-100 text-rose-800 border-rose-200',
            'Sedang' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Rendah' => 'bg-slate-100 text-slate-700 border-slate-200',
            'Mendesak' => 'bg-red-100 text-red-800 border-red-300',
        ];
        $utama = $data->first();
    @endphp

    {{-- Judul --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-[#e2e7ff]">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[#022448] text-[28px]">verified_user</span>
                <h1 class="text-xl font-extrabold text-[#0f172a] tracking-tight">Persetujuan Pengadaan</h1>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">{{ $menunggu }} Menunggu Keputusan</span>
            </div>
            <p class="text-xs text-[#515f74] mt-1 leading-relaxed">Tinjau dan berikan persetujuan untuk permohonan pengadaan sarana dan prasarana.</p>
        </div>
        <div class="flex items-center gap-2.5">
            @if ($utama)
                <button type="button" class="btn-tinjau px-3.5 py-2 rounded-lg bg-[#022448] hover:bg-[#1e3a5f] text-white text-xs font-semibold flex items-center gap-1.5 shadow-sm transition-all"
                    data-id="{{ $utama->id }}"
                    data-nomor="{{ $utama->nomor_pengadaan }}"
                    data-tanggal="{{ $utama->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}"
                    data-pengaju="{{ $utama->user?->name }}"
                    data-paket="{{ $utama->detail->first()?->nama_barang }}"
                    data-spesifikasi="{{ $utama->detail->first()?->spesifikasi }}"
                    data-biaya="{{ $rupiah($utama->detail->sum('subtotal')) }}"
                    data-prioritas="{{ $utama->perencanaan?->prioritas?->name }}">
                    <span class="material-symbols-outlined text-lg">rate_review</span>
                    <span>Tinjau Usulan Utama</span>
                </button>
            @endif
        </div>
    </div>

    @if (session('status'))
        <div class="mt-4 flex items-start gap-3 p-4 bg-[#ecfdf5] border border-emerald-200 rounded-xl">
            <span class="material-symbols-outlined text-emerald-600 text-xl shrink-0">check_circle</span>
            <p class="text-sm text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    {{-- 4 kartu KPI --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white border border-amber-200/80 rounded-xl p-4 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Usulan Menunggu</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-[#0f172a]" style="font-family: 'JetBrains Mono', monospace;">{{ $menunggu }}</span>
                <span class="text-[11px] text-amber-700 font-medium" style="font-family: 'JetBrains Mono', monospace;">Permohonan</span>
            </div>
            <div class="mt-1 text-[11px] text-[#515f74] flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Perlu persetujuan
            </div>
        </div>

        <div class="bg-white border border-emerald-200/80 rounded-xl p-4 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Disetujui Bulan Ini</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">task_alt</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-emerald-700" style="font-family: 'JetBrains Mono', monospace;">{{ $disetujuiBulanIni }}</span>
                <span class="text-[11px] text-emerald-700 font-medium" style="font-family: 'JetBrains Mono', monospace;">Permohonan</span>
            </div>
            <div class="mt-1 text-[11px] text-[#515f74] flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
            </div>
        </div>

        <div class="bg-white border border-rose-200/80 rounded-xl p-4 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Ditolak / Revisi</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">rule_folder</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-rose-700" style="font-family: 'JetBrains Mono', monospace;">{{ $ditolak }}</span>
                <span class="text-[11px] text-rose-700 font-medium" style="font-family: 'JetBrains Mono', monospace;">Permohonan</span>
            </div>
            <div class="mt-1 text-[11px] text-[#515f74] flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Perlu revisi
            </div>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#515f74]">Total Anggaran Diajukan</span>
                <div class="w-8 h-8 rounded-lg bg-[#1e3a5f]/10 text-[#022448] flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">payments</span>
                </div>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-xl font-extrabold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $rupiahJt($totalEstimasi) }}</span>
            </div>
            <div class="mt-1 text-[11px] text-[#515f74] flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#022448]"></span> Total estimasi biaya
            </div>
        </div>
    </div>

    {{-- Tabel antrean --}}
    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-[#e2e7ff] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#022448] text-xl">table_chart</span>
                <h2 class="text-sm font-bold text-[#0f172a]">Daftar Pengajuan Pengadaan Menunggu Persetujuan</h2>
            </div>
            <form method="GET" action="{{ route('persetujuan.index') }}" class="flex items-center gap-2 text-xs">
                <span class="text-[#515f74]">Penyortiran:</span>
                <select name="prioritas" onchange="this.form.submit()" class="bg-white border border-[#e2e7ff] rounded-lg px-2.5 py-1.5 text-xs text-[#0f172a] font-medium focus:outline-none">
                    <option value="">Prioritas Tertinggi</option>
                    @foreach (['Mendesak', 'Tinggi', 'Sedang', 'Rendah'] as $pr)
                        <option value="{{ $pr }}" @selected(request('prioritas') === $pr)>{{ $pr }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#f1f5f9]/60 text-[#515f74] uppercase tracking-wider text-[11px] font-semibold border-b border-[#e2e7ff]">
                        <th class="py-3 px-4">No. Pengadaan</th>
                        <th class="py-3 px-4">Tanggal Usulan</th>
                        <th class="py-3 px-4">Pengaju &amp; Unit Kerja</th>
                        <th class="py-3 px-4">Nama Paket &amp; Lingkup</th>
                        <th class="py-3 px-4">Prioritas</th>
                        <th class="py-3 px-4 text-right">Total Estimasi Biaya</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($data as $p)
                        @php
                            $namaPrioritas = $p->perencanaan?->prioritas?->name ?? '-';
                            $paket = $p->detail->first();
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $p->nomor_pengadaan }}</td>
                            <td class="py-3.5 px-4 text-[#515f74] whitespace-nowrap">{{ $p->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-semibold">{{ $p->user?->name ?? '-' }}</p>
                                <p class="text-[11px] text-[#515f74]">{{ $p->user?->role?->name ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="font-semibold">{{ $paket?->nama_barang ?? '-' }}{{ $p->detail->count() > 1 ? ' (+'.($p->detail->count() - 1).' item)' : '' }}</p>
                                <p class="text-[11px] text-[#515f74] truncate">{{ \Illuminate\Support\Str::limit($p->alasan, 70) }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgePrioritas[$namaPrioritas] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ $namaPrioritas }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-emerald-700 text-sm" style="font-family: 'JetBrains Mono', monospace;">
                                {{ $rupiah($p->detail->sum('subtotal')) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-300">Menunggu Persetujuan</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" class="btn-tinjau px-3 py-1.5 rounded-lg bg-[#022448] hover:bg-[#1e3a5f] text-white font-semibold items-center gap-1 text-xs transition-all shadow-xs inline-flex"
                                    data-id="{{ $p->id }}"
                                    data-nomor="{{ $p->nomor_pengadaan }}"
                                    data-tanggal="{{ $p->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y') }}"
                                    data-pengaju="{{ $p->user?->name }}"
                                    data-paket="{{ $paket?->nama_barang }}"
                                    data-spesifikasi="{{ $paket?->spesifikasi }}"
                                    data-biaya="{{ $rupiah($p->detail->sum('subtotal')) }}">
                                    <span class="material-symbols-outlined text-base">gavel</span>
                                    <span>Tinjau &amp; Otorisasi</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-4 text-center text-sm text-[#515f74]">Tidak ada usulan menunggu keputusan. Kerja bagus!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3 border-t border-[#e2e7ff] flex items-center justify-between text-xs text-[#515f74] bg-[#f1f5f9]/20">
            <span>Menampilkan <strong>{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> usulan pengadaan aktif</span>
            <div class="flex items-center gap-1">
                @if ($data->onFirstPage())
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed">Sebelumnya</span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-medium">Sebelumnya</a>
                @endif
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="px-2.5 py-1 rounded border border-[#e2e7ff] hover:bg-[#f1f5f9] font-medium">Selanjutnya</a>
                @else
                    <span class="px-2.5 py-1 rounded border border-[#e2e7ff] text-[#94a3b8] cursor-not-allowed">Selanjutnya</span>
                @endif
            </div>
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

    {{-- Modal konfirmasi persetujuan --}}
    <div id="modalKonfirmasi" class="fixed inset-0 z-50 bg-[#022448]/70 backdrop-blur-xs items-center justify-center p-4 overflow-y-auto hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-[#e2e7ff] flex flex-col gap-4 my-auto">
            <div class="flex items-start justify-between border-b border-[#e2e7ff] pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#022448] text-white flex items-center justify-center shadow-xs">
                        <span class="material-symbols-outlined text-2xl">gavel</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-[#0f172a]">Konfirmasi Persetujuan Pengadaan</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">Menunggu Persetujuan</span>
                        </div>
                        <p class="text-xs text-[#515f74] mt-0.5">Tinjau detail permohonan pengadaan sarana dan prasarana.</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModalKonfirmasi()" class="p-1.5 text-[#515f74] hover:text-[#022448] rounded-lg hover:bg-[#f1f5f9] transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <div class="bg-[#f1f5f9]/60 rounded-xl p-4 border border-[#e2e7ff] grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-[#515f74] font-medium text-[11px]">Nomor &amp; Tanggal Pengajuan:</span>
                    <p class="font-bold text-[#022448] text-sm mt-0.5" style="font-family: 'JetBrains Mono', monospace;"><span id="konfirmasiNomor">-</span> <span class="text-[#515f74] font-sans text-xs font-normal">• <span id="konfirmasiTanggal">-</span></span></p>
                </div>
                <div>
                    <span class="text-[#515f74] font-medium text-[11px]">Estimasi Biaya:</span>
                    <p class="font-bold text-emerald-700 text-base mt-0.5" style="font-family: 'JetBrains Mono', monospace;" id="konfirmasiBiaya">-</p>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-[#515f74] font-medium text-[11px]">Pengaju:</span>
                    <p class="font-semibold text-[#0f172a] mt-0.5" id="konfirmasiPengaju">-</p>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-[#515f74] font-medium text-[11px]">Nama Barang &amp; Spesifikasi:</span>
                    <p class="font-semibold text-[#0f172a] mt-0.5 leading-relaxed" id="konfirmasiPaket">-</p>
                </div>
            </div>

            <form id="formKeputusan" method="POST" class="flex flex-col gap-3">
                @csrf
                <div>
                    <div class="flex items-center justify-between">
                        <label for="catatanKeputusan" class="text-xs font-bold text-[#0f172a]">Catatan / Alasan Keputusan:</label>
                        <span id="charCounter" class="text-[11px] text-[#515f74]" style="font-family: 'JetBrains Mono', monospace;">0 / 300 karakter</span>
                    </div>
                    <textarea id="catatanKeputusan" name="catatan" rows="2" maxlength="300" oninput="document.getElementById('charCounter').textContent = this.value.length + ' / 300 karakter'" placeholder="Tuliskan catatan persetujuan atau alasan jika permohonan ditolak..." class="mt-1 w-full p-3 rounded-lg border border-[#e2e7ff] bg-[#f1f5f9] text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 pt-3 border-t border-[#e2e7ff]">
                    <button type="button" onclick="tutupModalKonfirmasi()" class="w-full sm:w-auto px-4 py-2 rounded-lg border border-[#e2e7ff] text-[#515f74] font-semibold hover:bg-[#f1f5f9] transition-colors text-xs">Batal</button>
                    <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                        <button type="submit" formaction="" id="btnTolak" class="flex-1 sm:flex-initial px-4 py-2 rounded-lg bg-[#dc2626] hover:bg-red-700 text-white font-semibold transition-colors text-xs flex items-center justify-center gap-1.5 shadow-xs">
                            <span class="material-symbols-outlined text-base">cancel</span>
                            <span>Tolak</span>
                        </button>
                        <button type="submit" formaction="" id="btnSetujui" class="flex-1 sm:flex-initial px-5 py-2 rounded-lg bg-[#16a34a] hover:bg-emerald-700 text-white font-bold transition-colors text-xs flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span>Setujui</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modalKonfirmasi = document.getElementById('modalKonfirmasi');

        function bukaModalKonfirmasi(d) {
            document.getElementById('konfirmasiNomor').textContent = d.nomor;
            document.getElementById('konfirmasiTanggal').textContent = d.tanggal;
            document.getElementById('konfirmasiBiaya').textContent = d.biaya;
            document.getElementById('konfirmasiPengaju').textContent = d.pengaju;
            document.getElementById('konfirmasiPaket').textContent = d.paket + (d.spesifikasi ? ' - ' + d.spesifikasi : '');
            document.getElementById('btnSetujui').formAction = d.setujui;
            document.getElementById('btnTolak').formAction = d.tolak;
            document.getElementById('catatanKeputusan').value = '';
            document.getElementById('charCounter').textContent = '0 / 300 karakter';
            modalKonfirmasi.classList.remove('hidden');
            modalKonfirmasi.classList.add('flex');
        }

        function tutupModalKonfirmasi() {
            modalKonfirmasi.classList.add('hidden');
            modalKonfirmasi.classList.remove('flex');
        }

        const urlSetujui = @json(route('persetujuan.setujui', ['pengadaan' => 0]));
        const urlTolak = @json(route('persetujuan.tolak', ['pengadaan' => 0]));

        document.querySelectorAll('.btn-tinjau').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                bukaModalKonfirmasi({
                    nomor: d.nomor,
                    tanggal: d.tanggal,
                    pengaju: d.pengaju,
                    paket: d.paket,
                    spesifikasi: d.spesifikasi,
                    biaya: d.biaya,
                    setujui: urlSetujui.replace('/0/setujui', '/' + d.id + '/setujui'),
                    tolak: urlTolak.replace('/0/tolak', '/' + d.id + '/tolak'),
                });
            });
        });
    </script>
</x-layouts::app>
