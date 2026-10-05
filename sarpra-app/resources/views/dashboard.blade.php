<x-layouts::app.sarpra :title="__('Dashboard')" :notifBelumDibaca="$notifBelumDibaca">
    {{-- Sapaan --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex flex-col">
            <div class="flex items-center gap-1.5 mb-1">
                <span class="inline-block w-2 h-2 rounded-full bg-[#00422c]"></span>
                <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider">Konsol Operasional Pusat</span>
            </div>
            <h1 class="text-[28px] leading-9 font-semibold text-[#1e3a5f] tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-[#43474e] mt-0.5">Ringkasan metrik operasional sarana dan prasarana terpadu hari ini, {{ $tanggalHariIni }}</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="inline-flex p-1 bg-[#e2e7ff] rounded-lg shadow-sm" id="time-filter-group">
                <button class="filter-btn px-4 py-1.5 rounded text-xs font-semibold bg-white text-[#1e3a5f] shadow-sm transition-all" data-range="today" type="button">Hari Ini</button>
                <button class="filter-btn px-4 py-1.5 rounded text-xs font-semibold text-[#515f74] hover:text-[#1e3a5f] transition-all" data-range="7d" type="button">7 Hari</button>
                <button class="filter-btn px-4 py-1.5 rounded text-xs font-semibold text-[#515f74] hover:text-[#1e3a5f] transition-all" data-range="30d" type="button">30 Hari</button>
            </div>
            <button class="flex items-center gap-1.5 px-5 py-2 bg-[#1e3a5f] text-white rounded-lg shadow-sm hover:bg-[#022448] transition-all active:scale-95" id="refresh-data-btn" type="button">
                <span class="material-symbols-outlined text-lg transition-transform duration-500" id="refresh-icon">sync</span>
                <span class="text-xs font-semibold">Segarkan Data</span>
            </button>
        </div>
    </div>

    {{-- Kartu metrik --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="p-6 rounded-xl bg-white shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#d5e3ff]/30 rounded-full blur-xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-[#515f74] tracking-wide uppercase">Total Pengguna Aktif</span>
                    <span class="text-4xl font-bold text-[#1e3a5f] mt-1 tracking-tight">{{ $totalAktif }}</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#e2e7ff] text-[#1e3a5f] flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">group</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 mt-auto">
                <div class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-[#85f8c4]/40 text-[#005137]">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    <span class="text-[13px] font-medium" style="font-family: 'JetBrains Mono', monospace;">{{ $totalPengguna }}</span>
                </div>
                <span class="text-xs text-[#515f74]">total akun terdaftar</span>
            </div>
        </div>

        <div class="p-6 rounded-xl bg-white shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#85f8c4]/20 rounded-full blur-xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-[#515f74] tracking-wide uppercase">Modul SARPRA</span>
                    <span class="text-4xl font-bold text-[#1e3a5f] mt-1 tracking-tight">6 Modul</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#e2e7ff] text-[#1e3a5f] flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">layers</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 mt-auto">
                <div class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-[#eaedff] text-[#1e3a5f]">
                    <span class="w-2 h-2 rounded-full bg-[#3fb687] animate-pulse"></span>
                    <span class="text-[13px] font-bold" style="font-family: 'JetBrains Mono', monospace;">Semua Aktif</span>
                </div>
                <span class="text-xs text-[#3fb687] font-medium">Perencanaan s/d Laporan</span>
            </div>
        </div>

        <div class="p-6 rounded-xl bg-white shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#d5e3fc]/40 rounded-full blur-xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-[#515f74] tracking-wide uppercase">Aktivitas Hari Ini</span>
                    <span class="text-4xl font-bold text-[#1e3a5f] mt-1 tracking-tight">{{ $aktivitasHariIni }}</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#e2e7ff] text-[#1e3a5f] flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">analytics</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 mt-auto">
                <div class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-[#85f8c4]/40 text-[#005137]">
                    <span class="material-symbols-outlined text-sm">north_east</span>
                    <span class="text-[13px] font-bold" style="font-family: 'JetBrains Mono', monospace;">{{ $aktivitasHariIni }}</span>
                </div>
                <span class="text-xs text-[#515f74]">peristiwa hari ini</span>
            </div>
        </div>

        <div class="p-6 rounded-xl bg-white shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#d5e3ff]/20 rounded-full blur-xl pointer-events-none group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-[#515f74] tracking-wide uppercase">Status Sistem</span>
                    <span class="text-base font-bold text-[#1e3a5f] mt-2 tracking-tight">Siap Digunakan</span>
                </div>
                <div class="w-11 h-11 rounded-lg bg-[#e2e7ff] text-[#1e3a5f] flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">verified</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 mt-auto">
                <div class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-[#f2f3ff] text-[#515f74] text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-[#3fb687]"></span>
                    <span>Aktif</span>
                </div>
                <span class="text-xs text-[#3fb687] font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">check_circle</span> Operasional Normal
                </span>
            </div>
        </div>
    </section>

    {{-- Aksi cepat --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[#1e3a5f] text-xl">bolt</span>
            <h2 class="text-base font-bold text-[#1e3a5f] tracking-tight">Aksi Cepat Administrator</h2>
        </div>
        <span class="text-xs font-semibold text-[#515f74] hidden sm:block">Pintasan tugas administratif esensial</span>
    </div>
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a class="p-6 rounded-xl bg-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between group" href="#">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-[#1e3a5f] text-white flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[26px]">person_add</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-[#1e3a5f]">Tambah Pengguna Baru</span>
                    <span class="text-xs text-[#515f74]">Registrasi Terverifikasi</span>
                </div>
            </div>
            <p class="text-sm text-[#43474e] line-clamp-2">Registrasi akun untuk Pengelola Sarana &amp; Prasarana atau Pimpinan / Pejabat Berwenang.</p>
            <div class="flex items-center gap-1 mt-5 text-xs font-semibold text-[#1e3a5f] group-hover:gap-2 transition-all">
                <span>Buka Formulir</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </div>
        </a>
        <a class="p-6 rounded-xl bg-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between group" href="#">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-[#515f74] text-white flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[26px]">inventory_2</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-[#1e3a5f]">Kelola Data Master</span>
                    <span class="text-xs text-[#515f74]">Kamus Data &amp; Taksonomi</span>
                </div>
            </div>
            <p class="text-sm text-[#43474e] line-clamp-2">Kategori barang, lokasi gedung/ruangan, serta satuan aset terstandar dalam ekosistem SARPRA.</p>
            <div class="flex items-center gap-1 mt-5 text-xs font-semibold text-[#515f74] group-hover:gap-2 transition-all">
                <span>Masuk Master Data</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </div>
        </a>
        <a class="p-6 rounded-xl bg-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between group" href="#">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-lg bg-[#e2e7ff] text-[#1e3a5f] flex items-center justify-center group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[26px]">history</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-[#1e3a5f]">Lihat Riwayat Aktivitas</span>
                    <span class="text-xs text-[#515f74]">Log Audit Terintegrasi</span>
                </div>
            </div>
            <p class="text-sm text-[#43474e] line-clamp-2">Log audit perubahan data, mutasi status sarpras, dan aktivitas sistem dari seluruh peran.</p>
            <div class="flex items-center gap-1 mt-5 text-xs font-semibold text-[#1e3a5f] group-hover:gap-2 transition-all">
                <span>Buka Log Aktivitas</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </div>
        </a>
    </section>

    {{-- Aktivitas + peran --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7 bg-white rounded-xl shadow-sm p-6 md:p-8 flex flex-col">
            <div class="flex items-center justify-between pb-5 mb-5">
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[#1e3a5f] text-xl">history_toggle_off</span>
                        <h2 class="text-base font-bold text-[#1e3a5f] tracking-tight">Aktivitas Pengguna Terkini</h2>
                    </div>
                    <span class="text-xs text-[#515f74] mt-0.5">Aliran rekaman mutasi data dari seluruh alur kerja SARPRA</span>
                </div>
                <a class="hidden sm:inline-flex items-center gap-1 px-4 py-1.5 bg-[#f2f3ff] text-[#1e3a5f] hover:bg-[#eaedff] rounded-lg text-xs font-semibold transition-colors" href="#">
                    <span>Lihat Riwayat Lengkap</span>
                    <span class="material-symbols-outlined text-base">chevron_right</span>
                </a>
            </div>
            <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#e2e7ff]">
                @forelse ($aktivitasTerkini as $a)
                    <div class="relative">
                        <span class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full bg-white ring-4 ring-[#1e3a5f]"></span>
                        <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-bold text-[#131b2e]">{{ $a['nama'] }}</span>
                                <span class="px-1.5 py-0.5 rounded bg-[#eaedff] text-[#1e3a5f] text-[11px] font-semibold">{{ $a['modul'] }}</span>
                            </div>
                            <span class="text-xs text-[#515f74] shrink-0" style="font-family: 'JetBrains Mono', monospace;">{{ $a['waktu'] }}</span>
                        </div>
                        <p class="text-sm text-[#43474e] mt-1">{{ $a['deskripsi'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-[#515f74]">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-5 bg-white rounded-xl shadow-sm p-6 md:p-8 flex flex-col">
            <div class="flex items-center justify-between pb-4">
                <div class="flex flex-col">
                    <h2 class="text-base font-bold text-[#1e3a5f] tracking-tight">Ringkasan Pengguna per Peran</h2>
                    <span class="text-xs text-[#515f74] mt-0.5">Proporsi hak akses dalam ekosistem SARPRA</span>
                </div>
                <div class="p-1.5 rounded bg-[#f2f3ff] text-[#515f74]" title="Metrik Pengguna SARPRA">
                    <span class="material-symbols-outlined text-lg">pie_chart</span>
                </div>
            </div>
            <div class="relative flex items-center justify-center my-6">
                <svg aria-label="Diagram Donut Distribusi Peran" class="w-48 h-48 -rotate-90" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" fill="transparent" r="60" stroke="#eaedff" stroke-width="18"></circle>
                    @foreach ($peran as $p)
                        <circle cx="80" cy="80" fill="transparent" r="60" stroke="{{ $p['segmen']['warna'] }}" stroke-dasharray="{{ $p['segmen']['panjang'] }} 377" stroke-dashoffset="{{ $p['segmen']['offset'] }}" stroke-width="18"></circle>
                    @endforeach
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-[28px] leading-9 font-semibold text-[#1e3a5f]">{{ $totalPengguna }}</span>
                    <span class="text-xs font-semibold text-[#515f74] uppercase tracking-wider mt-1">Total Pengguna</span>
                </div>
            </div>
            <div class="space-y-2 mb-6">
                @foreach ($peran as $p)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-[#f2f3ff] hover:bg-[#eaedff] transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $p['segmen']['warna'] }};"></span>
                            <span class="text-sm font-medium text-[#131b2e]">{{ $p['nama'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-[13px] font-bold text-[#1e3a5f]" style="font-family: 'JetBrains Mono', monospace;">{{ $p['jumlah'] }} Akun</span>
                            <span class="text-xs text-[#515f74] w-10 text-right">{{ $p['pct'] }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-auto p-4 rounded-lg bg-[#eaedff] flex items-start gap-3">
                <span class="material-symbols-outlined text-xl text-[#1e3a5f] shrink-0 mt-0.5">security</span>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-[#1e3a5f]">Konfigurasi Hak Akses 3 Peran</span>
                    <p class="text-xs text-[#515f74] mt-0.5 leading-relaxed">
                        Pembagian wewenang selaras alur SARPRA: <strong class="text-[#131b2e] font-medium">Admin</strong> (Manajemen Sistem &amp; Pengguna), <strong class="text-[#131b2e] font-medium">Pengelola</strong> (Operasional Sarpras), dan <strong class="text-[#131b2e] font-medium">Pimpinan</strong> (Verifikasi &amp; Otorisasi).
                    </p>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-8 pt-5 pb-2 border-t border-[#eaedff] flex flex-col sm:flex-row items-center justify-between gap-3 text-[#515f74]">
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-[#1e3a5f]">SARPRA</span>
            <span class="text-[#c4c6cf]">•</span>
            <span class="text-xs">Sistem Terpadu Pengelolaan Sarana dan Prasarana</span>
        </div>
        <div class="flex items-center gap-3 text-xs font-semibold">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[#3fb687]"></span> Operasional Aktif</span>
            <span class="text-[#c4c6cf]">•</span>
            <span>Hak Cipta © 2026</span>
        </div>
    </footer>

    <script>
        (function() {
            const filterButtons = document.querySelectorAll('#time-filter-group .filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterButtons.forEach(b => {
                        b.classList.remove('bg-white', 'text-[#1e3a5f]', 'shadow-sm');
                        b.classList.add('text-[#515f74]');
                    });
                    btn.classList.add('bg-white', 'text-[#1e3a5f]', 'shadow-sm');
                    btn.classList.remove('text-[#515f74]');
                });
            });

            const refreshBtn = document.getElementById('refresh-data-btn');
            const refreshIcon = document.getElementById('refresh-icon');
            if (refreshBtn && refreshIcon) {
                refreshBtn.addEventListener('click', () => {
                    refreshIcon.classList.add('rotate-180');
                    refreshBtn.classList.add('opacity-75');
                    setTimeout(() => window.location.reload(), 600);
                });
            }
        })();
    </script>
</x-layouts::app.sarpra>
