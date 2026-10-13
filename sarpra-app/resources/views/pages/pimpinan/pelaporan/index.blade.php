<x-layouts::app :title="__('Pelaporan')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="pelaporan" judulHalaman="Pelaporan">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Pelaporan</h1>
            <p class="text-sm text-[#515f74]">Unduh laporan sarana dan prasarana per modul.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        <div class="bg-white border border-[#e2e7ff] rounded-xl p-6 shadow-xs flex flex-col">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#d5e3fc]/70 text-[#022448] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">inventory_2</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f172a]">Laporan Inventaris</h2>
                    <p class="text-xs text-[#515f74]">{{ $ringkasan['barang'] }} barang tercatat</p>
                </div>
            </div>
            <p class="text-xs text-[#515f74] mt-3">Daftar barang: kode, nama, kategori, lokasi, kondisi, status, jumlah, dan harga perolehan.</p>
            <a href="{{ route('laporan.barang') }}" class="mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors">
                <span class="material-symbols-outlined text-base">file_download</span>
                <span>Unduh CSV</span>
            </a>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-6 shadow-xs flex flex-col">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#d5e3fc]/70 text-[#022448] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f172a]">Laporan Pengadaan Menunggu</h2>
                    <p class="text-xs text-[#515f74]">{{ $ringkasan['pengadaan'] }} pengajuan tercatat</p>
                </div>
            </div>
            <p class="text-xs text-[#515f74] mt-3">Usulan menunggu keputusan: nomor, pengaju, paket, dan estimasi biaya.</p>
            <a href="{{ route('dashboard.ekspor-eksekutif') }}" class="mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors">
                <span class="material-symbols-outlined text-base">file_download</span>
                <span>Unduh CSV</span>
            </a>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-6 shadow-xs flex flex-col">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#d5e3fc]/70 text-[#022448] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">history</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f172a]">Laporan Aktivitas</h2>
                    <p class="text-xs text-[#515f74]">Log audit lintas modul</p>
                </div>
            </div>
            <p class="text-xs text-[#515f74] mt-3">Riwayat aktivitas pengguna: waktu, pelaku, aksi, dan status.</p>
            <a href="{{ route('dashboard.ekspor') }}" class="mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#022448] text-white text-xs font-semibold rounded-lg hover:bg-[#1e3a5f] transition-colors">
                <span class="material-symbols-outlined text-base">file_download</span>
                <span>Unduh CSV</span>
            </a>
        </div>

        <div class="bg-white border border-[#e2e7ff] rounded-xl p-6 shadow-xs flex flex-col">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-[#d5e3fc]/70 text-[#022448] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">build</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f172a]">Laporan Pemeliharaan</h2>
                    <p class="text-xs text-[#515f74]">{{ $ringkasan['pemeliharaan'] }} tiket tercatat</p>
                </div>
            </div>
            <p class="text-xs text-[#515f74] mt-3">Segera hadir: ekspor tiket pemeliharaan per status dan periode.</p>
            <button type="button" disabled class="mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#f1f5f9] text-[#94a3b8] text-xs font-semibold rounded-lg cursor-not-allowed">
                <span class="material-symbols-outlined text-base">hourglass_top</span>
                <span>Segera Hadir</span>
            </button>
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
