<x-layouts::app :title="__('Pengajuan Pengadaan Baru')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pengadaan" judulHalaman="Pengadaan">
    {{-- Judul --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-[#e2e7ff]">
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2 py-0.5 bg-[#1e3a5f] text-white text-[10px] font-bold rounded uppercase tracking-wider">Modul Pengadaan • Dokumen Permohonan</span>
                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-semibold rounded-full flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Status: Baru (Drafting)</span>
                </div>
                <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Pengajuan Pengadaan Sarana &amp; Prasarana Baru</h1>
                <p class="text-sm text-[#515f74] leading-relaxed max-w-3xl">Ajukan usulan pembelian atau pengadaan sarana dan prasarana baru dengan rincian barang dan estimasi biaya yang jelas.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('pengadaan.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-[#515f74] hover:text-[#022448] bg-white border border-[#e2e7ff] rounded-lg hover:bg-[#f1f5f9] transition-colors shadow-xs">
                    <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Daftar Pengadaan
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="flex items-start gap-3 p-4 bg-[#ffdad6]/40 border border-[#ba1a1a]/30 rounded-xl">
                <span class="material-symbols-outlined text-[#ba1a1a] text-xl shrink-0">error</span>
                <p class="text-sm text-[#93000a]">{{ $errors->first() }}</p>
            </div>
        @endif

        <form id="formPengadaan" method="POST" action="{{ route('pengadaan.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf
            <input type="hidden" name="aksi" id="aksiForm" value="draft">

            {{-- Kolom kiri --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                {{-- 01 Informasi --}}
                <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col gap-5">
                    <div class="flex items-center justify-between border-b border-[#e2e7ff] pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-[#1e3a5f] text-white text-xs font-bold" style="font-family: 'JetBrains Mono', monospace;">01</span>
                            <h2 class="font-bold text-base text-[#022448]">Informasi Pengajuan</h2>
                        </div>
                        <span class="text-xs text-[#475569] font-medium">Wajib diisi <span class="text-[#dc2626]">*</span></span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="namaPengajuan" class="text-xs font-bold text-[#0f172a] flex items-center justify-between">
                            <span>Nama Pengajuan / Kebutuhan Barang <span class="text-[#dc2626]">*</span></span>
                            <span class="text-[11px] font-normal text-[#515f74]">Tulis judul atau uraian ringkas pengadaan</span>
                        </label>
                        <input type="text" id="namaPengajuan" name="nama_pengajuan" value="{{ old('nama_pengajuan') }}" maxlength="150" placeholder="Contoh: Pengadaan 3 Unit Laptop Administrasi TU" class="w-full px-3 py-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="dasarPerencanaan" class="text-xs font-bold text-[#0f172a] flex items-center justify-between">
                            <span>Dasar Perencanaan</span>
                            <span class="text-[11px] font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full flex items-center gap-1"><span class="material-symbols-outlined text-xs">sync</span> Terhubung RKBM Otomatis</span>
                        </label>
                        <select id="dasarPerencanaan" name="perencanaan_id" class="w-full px-3 py-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none focus:border-[#1e3a5f] cursor-pointer">
                            <option value="">-- Tanpa dasar perencanaan --</option>
                            @foreach ($perencanaans as $pr)
                                <option value="{{ $pr->id }}" @selected(old('perencanaan_id') == $pr->id)>PRN-{{ $pr->created_at->format('Y') }}-{{ str_pad($pr->id, 3, '0', STR_PAD_LEFT) }}: {{ \Illuminate\Support\Str::limit($pr->nama_kebutuhan, 45) }} - {{ $pr->jumlah }} unit · Prioritas: {{ $pr->prioritas?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="jenisPengadaan" class="text-xs font-bold text-[#0f172a]">Jenis Pengadaan <span class="text-[#dc2626]">*</span></label>
                            <select id="jenisPengadaan" name="jenis_pengadaan_id" required class="w-full px-3 py-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none focus:border-[#1e3a5f] cursor-pointer">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach ($jeniss as $j)
                                    <option value="{{ $j->id }}" @selected(old('jenis_pengadaan_id') == $j->id)>{{ $j->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="tglPengajuan" class="text-xs font-bold text-[#0f172a]">Tanggal Pengajuan <span class="text-[#dc2626]">*</span></label>
                            <input type="date" id="tglPengajuan" name="tanggal_pengajuan" required value="{{ old('tanggal_pengajuan', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none focus:border-[#1e3a5f]">
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="alasanPengadaan" class="text-xs font-bold text-[#0f172a]">Alasan / Urgensi Pengadaan</label>
                        <textarea id="alasanPengadaan" name="alasan" rows="3" placeholder="Uraikan alasan urgensi kebutuhan pengadaan sarana dan prasarana..." class="w-full p-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs leading-relaxed text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">{{ old('alasan') }}</textarea>
                    </div>
                </div>

                {{-- 02 Rincian --}}
                <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e2e7ff] pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-[#1e3a5f] text-white text-xs font-bold" style="font-family: 'JetBrains Mono', monospace;">02</span>
                            <div>
                                <h2 class="font-bold text-base text-[#022448]">Daftar Rincian Pengadaan &amp; Spesifikasi Teknis</h2>
                                <p class="text-xs text-[#515f74]">Cantumkan rincian merk, spesifikasi minimum, jumlah, dan estimasi harga perolehan.</p>
                            </div>
                        </div>
                        <button type="button" id="btnTambahBaris" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors shadow-xs self-start sm:self-auto">
                            <span class="material-symbols-outlined text-base">add</span> Tambah Baris Barang
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-[#e2e7ff] rounded-lg">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-[#f1f5f9] text-[#515f74] font-semibold border-b border-[#e2e7ff]">
                                <tr>
                                    <th class="p-2.5 text-center w-12">No</th>
                                    <th class="p-2.5 min-w-[200px]">Nama Barang &amp; Spesifikasi Teknis</th>
                                    <th class="p-2.5 min-w-[130px]">Kategori</th>
                                    <th class="p-2.5 w-20 text-center">Jumlah</th>
                                    <th class="p-2.5 w-28">Satuan</th>
                                    <th class="p-2.5 w-40 text-right">Harga Satuan (Rp)</th>
                                    <th class="p-2.5 w-40 text-right">Subtotal (Rp)</th>
                                    <th class="p-2.5 w-12 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tubuhRincian" class="divide-y divide-[#e2e7ff] bg-white"></tbody>
                            <tfoot class="bg-[#f1f5f9]/80 border-t-2 border-[#e2e7ff] font-semibold">
                                <tr>
                                    <td colspan="8" class="px-4 py-2 text-[11px] italic text-[#515f74]">*tambahkan barang/perlengkapan jika diperlukan</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="p-3 text-right text-[#515f74] uppercase tracking-wider">Total Estimasi Nilai Pengajuan:</td>
                                    <td id="grandTotal" class="p-3 text-right font-extrabold text-base text-[#022448] whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex items-center gap-2 p-3 bg-[#d5e3fc]/40 rounded-lg border border-[#d5e3fc]">
                        <span class="material-symbols-outlined text-lg text-[#022448]">payments</span>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 text-xs">
                            <span class="font-bold text-[#022448]">Terbilang:</span>
                            <span id="teksTerbilang" class="italic text-[#475569]">Nol Rupiah</span>
                        </div>
                    </div>
                </div>

                {{-- 03 Dokumen --}}
                <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col gap-4">
                    <div class="flex items-center justify-between border-b border-[#e2e7ff] pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-[#1e3a5f] text-white text-xs font-bold" style="font-family: 'JetBrains Mono', monospace;">03</span>
                            <h2 class="font-bold text-base text-[#022448]">Dokumen Pendukung (Opsional)</h2>
                        </div>
                        <span class="text-xs text-[#515f74]">Format PDF, JPG, PNG maks. 10MB</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <input type="file" name="dokumen[]" multiple accept=".pdf,.jpg,.jpeg,.png,.xls,.xlsx,.doc,.docx" class="w-full text-xs text-[#0f172a] file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#022448] file:text-white file:text-xs file:font-semibold hover:file:bg-[#1e3a5f]">
                        <p class="text-[11px] text-[#515f74]">Berkas KAK, HPS/RAB, atau surat permohonan kebutuhan.</p>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan --}}
            <div class="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-20">
                <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col gap-4">
                    <div class="flex items-center justify-between border-b border-[#e2e7ff] pb-3">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#022448] text-xl">receipt_long</span>
                            <h3 class="font-bold text-sm text-[#022448]">Ringkasan Pengajuan</h3>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-full">Draf</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#515f74]">Total Rincian Barang:</span>
                            <span class="font-semibold text-[#0f172a]"><span id="ringkasItem">0 Item</span> <span class="text-[#515f74] font-normal">(<span id="ringkasUnit">0</span> Unit fisik)</span></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#515f74]">Unit Pemohon:</span>
                            <span class="font-medium text-[#0f172a]">{{ auth()->user()->role?->name }}</span>
                        </div>
                        <div class="h-px bg-[#e2e7ff] my-0.5"></div>
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-[#515f74]">Total Estimasi Biaya:</span>
                            <span id="ringkasTotal" class="font-extrabold text-2xl text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">Rp 0</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-[#f1f5f9] border border-[#e2e7ff] text-[11px] text-[#515f74] flex items-start gap-2">
                            <span class="material-symbols-outlined text-[#022448] text-base shrink-0 mt-0.5">info</span>
                            <span>Biaya dihitung otomatis berdasarkan jumlah unit dan estimasi harga satuan pada tabel.</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[#e2e7ff] rounded-xl p-5 shadow-xs flex flex-col gap-4">
                    <div class="flex items-center gap-2 border-b border-[#e2e7ff] pb-3">
                        <span class="material-symbols-outlined text-[#022448] text-xl">account_tree</span>
                        <h3 class="font-bold text-sm text-[#022448]">Alur Persetujuan</h3>
                    </div>
                    <div class="relative flex flex-col gap-4 pl-4 border-l-2 border-[#1e3a5f]/30 ml-2">
                        <div class="relative flex flex-col gap-1">
                            <span class="absolute -left-[23px] top-0 flex items-center justify-center w-5 h-5 rounded-full bg-[#022448] text-white ring-4 ring-white"><span class="material-symbols-outlined text-xs">check</span></span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[#022448]">1. Pemohon</span>
                                <span class="text-[10px] bg-[#022448] text-white px-1.5 py-0.2 rounded font-semibold">Aktif</span>
                            </div>
                            <p class="text-[11px] text-[#515f74] leading-snug">{{ auth()->user()->name }} ({{ auth()->user()->role?->name }})</p>
                        </div>
                        <div class="relative flex flex-col gap-1">
                            <span class="absolute -left-[23px] top-0 flex items-center justify-center w-5 h-5 rounded-full bg-[#f1f5f9] text-[#515f74] text-[10px] font-bold ring-4 ring-white">2</span>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[#0f172a]">2. Pejabat Penyetujui / Pimpinan</span>
                                <span class="text-[10px] bg-[#f1f5f9] text-[#515f74] px-1.5 py-0.2 rounded font-medium">Menunggu Kirim</span>
                            </div>
                            <p class="text-[11px] text-[#515f74] leading-snug">Verifikasi kebutuhan operasional &amp; persetujuan pengadaan.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[#e2e7ff] rounded-xl p-4 shadow-sm flex flex-col gap-2.5">
                    <button type="button" id="btnKirim" class="w-full py-2.5 px-4 bg-[#022448] text-white text-xs font-bold rounded-lg hover:bg-[#1e3a5f] transition-all shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">send</span>
                        <span>Kirim Pengajuan</span>
                    </button>
                    <button type="button" id="btnDraft" class="w-full py-2 px-4 bg-[#f1f5f9] hover:bg-[#e2e7ff] text-[#515f74] hover:text-[#022448] text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">save</span>
                        <span>Simpan sebagai Draft</span>
                    </button>
                    <a href="{{ route('pengadaan.index') }}" class="w-full py-1.5 text-[#515f74] hover:text-[#dc2626] text-xs font-medium transition-colors text-center">Batalkan</a>
                </div>
            </div>
        </form>

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
    </div>

    {{-- Modal konfirmasi kirim --}}
    <div id="modalKonfirmasi" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white border border-[#e2e7ff] rounded-2xl max-w-md w-full p-6 shadow-xl flex flex-col gap-4">
            <div class="w-12 h-12 rounded-full bg-[#d5e3fc] flex items-center justify-center text-[#022448]">
                <span class="material-symbols-outlined text-[28px]">verified</span>
            </div>
            <div class="flex flex-col gap-1">
                <h3 class="text-base font-bold text-[#022448]">Konfirmasi Pengiriman Pengajuan</h3>
                <p class="text-xs text-[#515f74] leading-relaxed">Kirim pengajuan sejumlah <span id="modalTotal" class="font-bold text-[#022448]">Rp 0</span> ke Pimpinan? Draft yang dikirim tidak dapat diubah lagi.</p>
            </div>
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-[#e2e7ff]">
                <button type="button" id="btnBatalKirim" class="px-4 py-2 text-xs font-semibold text-[#515f74] hover:bg-[#f1f5f9] rounded-lg transition-colors">Periksa Kembali</button>
                <button type="button" id="btnYaKirim" class="px-4 py-2 text-xs font-bold bg-[#022448] text-white hover:bg-[#1e3a5f] rounded-lg transition-colors flex items-center gap-1.5 shadow-xs">
                    <span class="material-symbols-outlined text-base">send</span> Ya, Kirimkan
                </button>
            </div>
        </div>
    </div>

    <script>
        const formPengadaan = document.getElementById('formPengadaan');
        const tubuhRincian = document.getElementById('tubuhRincian');
        let nomorBaris = 0;

        const rupiahID = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');

        function angkaKeTerbilang(nilai) {
            nilai = Math.floor(Number(nilai) || 0);
            if (nilai === 0) return 'Nol Rupiah';
            const huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            function bagi(n) {
                if (n < 12) return huruf[n];
                if (n < 20) return bagi(n - 10) + ' Belas';
                if (n < 100) return bagi(Math.floor(n / 10)) + ' Puluh ' + huruf[n % 10];
                if (n < 200) return 'Seratus ' + bagi(n - 100);
                if (n < 1000) return bagi(Math.floor(n / 100)) + ' Ratus ' + bagi(n % 100);
                if (n < 2000) return 'Seribu ' + bagi(n - 1000);
                if (n < 1000000) return bagi(Math.floor(n / 1000)) + ' Ribu ' + bagi(n % 1000);
                if (n < 1000000000) return bagi(Math.floor(n / 1000000)) + ' Juta ' + bagi(n % 1000000);
                return bagi(Math.floor(n / 1000000000)) + ' Miliar ' + bagi(n % 1000000000);
            }
            return (bagi(nilai) + ' Rupiah').replace(/\s+/g, ' ').trim();
        }

        function hitungUlang() {
            let total = 0, item = 0, unit = 0;
            tubuhRincian.querySelectorAll('tr.baris-rincian').forEach((row, i) => {
                row.querySelector('.nomor-baris').textContent = i + 1;
                const qty = parseFloat(row.querySelector('.input-jumlah').value) || 0;
                const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
                const sub = qty * harga;
                total += sub;
                if (qty > 0) { item++; unit += qty; }
                row.querySelector('.subtotal-baris').textContent = rupiahID(sub);
            });
            document.getElementById('grandTotal').textContent = rupiahID(total);
            document.getElementById('ringkasTotal').textContent = rupiahID(total);
            document.getElementById('ringkasItem').textContent = item + ' Item';
            document.getElementById('ringkasUnit').textContent = unit;
            document.getElementById('teksTerbilang').textContent = angkaKeTerbilang(total);
            return total;
        }

        function tambahBaris(data = {}) {
            nomorBaris++;
            const tr = document.createElement('tr');
            tr.className = 'baris-rincian hover:bg-[#f1f5f9]/50 transition-colors';
            tr.innerHTML = `
                <td class="p-2.5 text-center text-[#515f74] nomor-baris" style="font-family: 'JetBrains Mono', monospace;">${nomorBaris}</td>
                <td class="p-2.5">
                    <input type="text" name="detail[${nomorBaris}][nama_barang]" required maxlength="150" value="${data.nama || ''}" placeholder="Nama barang..." class="w-full px-2.5 py-1.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs focus:outline-none focus:border-[#1e3a5f] mb-1">
                    <input type="text" name="detail[${nomorBaris}][spesifikasi]" value="${data.spesifikasi || ''}" placeholder="Spesifikasi teknis..." class="w-full px-2.5 py-1.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-[11px] focus:outline-none focus:border-[#1e3a5f]">
                    <select name="detail[${nomorBaris}][kategori_barang_id]" class="w-full mt-1 px-2 py-1.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-[11px] focus:outline-none focus:border-[#1e3a5f]">
                        <option value="">-- Kategori --</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-2.5">
                    <input type="number" name="detail[${nomorBaris}][jumlah]" min="1" value="${data.jumlah || 1}" required class="input-jumlah w-full px-2 py-1.5 text-center bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs font-semibold focus:outline-none focus:border-[#1e3a5f]" style="font-family: 'JetBrains Mono', monospace;">
                </td>
                <td class="p-2.5">
                    <select name="detail[${nomorBaris}][satuan_id]" required class="w-full px-2 py-1.5 bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs focus:outline-none focus:border-[#1e3a5f]">
                        @foreach ($satuans as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-2.5">
                    <input type="number" name="detail[${nomorBaris}][estimasi_harga]" min="0" step="0.01" value="${data.harga || 0}" required class="input-harga w-full px-2 py-1.5 text-right bg-[#f1f5f9] border border-[#e2e7ff] rounded text-xs font-semibold focus:outline-none focus:border-[#1e3a5f]" style="font-family: 'JetBrains Mono', monospace;">
                </td>
                <td class="p-2.5 text-right font-bold text-[#022448] subtotal-baris whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">Rp 0</td>
                <td class="p-2.5 text-center">
                    <button type="button" class="btn-hapus-baris p-1 text-[#515f74] hover:text-[#dc2626] hover:bg-red-50 rounded transition-colors" title="Hapus baris">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </td>`;
            tubuhRincian.appendChild(tr);
            tr.querySelectorAll('.input-jumlah, .input-harga').forEach(el => el.addEventListener('input', hitungUlang));
            tr.querySelector('.btn-hapus-baris').addEventListener('click', () => {
                if (tubuhRincian.querySelectorAll('tr.baris-rincian').length <= 1) {
                    alert('Minimal harus memiliki 1 rincian barang.');
                    return;
                }
                tr.remove();
                hitungUlang();
            });
            hitungUlang();
        }

        document.getElementById('btnTambahBaris').addEventListener('click', () => tambahBaris());
        tambahBaris();

        const modalKonfirmasi = document.getElementById('modalKonfirmasi');

        document.getElementById('btnKirim').addEventListener('click', () => {
            const rows = tubuhRincian.querySelectorAll('tr.baris-rincian');
            if (rows.length === 0) {
                alert('Tambahkan minimal 1 rincian barang.');
                return;
            }
            document.getElementById('modalTotal').textContent = document.getElementById('grandTotal').textContent;
            modalKonfirmasi.classList.remove('hidden');
            modalKonfirmasi.classList.add('flex');
        });

        document.getElementById('btnBatalKirim').addEventListener('click', () => {
            modalKonfirmasi.classList.add('hidden');
            modalKonfirmasi.classList.remove('flex');
        });

        document.getElementById('btnYaKirim').addEventListener('click', () => {
            document.getElementById('aksiForm').value = 'kirim';
            formPengadaan.submit();
        });

        document.getElementById('btnDraft').addEventListener('click', () => {
            document.getElementById('aksiForm').value = 'draft';
            formPengadaan.submit();
        });
    </script>
</x-layouts::app>
