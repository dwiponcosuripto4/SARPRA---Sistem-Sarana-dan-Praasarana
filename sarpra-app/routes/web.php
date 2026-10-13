<?php

use App\Models\Barang;
use App\Models\JenisPemeliharaan;
use App\Models\JenisPengadaan;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Lokasi;
use App\Models\Notifikasi;
use App\Models\Pemeliharaan;
use App\Models\Pengadaan;
use App\Models\Perencanaan;
use App\Models\Periode;
use App\Models\PersetujuanPengadaan;
use App\Models\Prioritas;
use App\Models\RiwayatAktivitas;
use App\Models\RiwayatPengadaan;
use App\Models\Role;
use App\Models\Satuan;
use App\Models\StatusBarang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    $aktivitasQuery = function (Request $request) {
        return RiwayatAktivitas::with(['user.role'])
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                $q->where(function ($w) use ($cari) {
                    $w->where('activity', 'like', $cari)
                        ->orWhere('description', 'like', $cari)
                        ->orWhere('module', 'like', $cari)
                        ->orWhereHas('user', fn ($u) => $u
                            ->where('name', 'like', $cari)
                            ->orWhere('email', 'like', $cari));
                });
            })
            ->when($request->filled('role'), function ($q) use ($request) {
                $q->whereHas('user.role', fn ($r) => $r->where('name', $request->input('role')));
            })
            ->when($request->filled('aktivitas'), function ($q) use ($request) {
                $q->where('activity', $request->input('aktivitas'));
            })
            ->latest();
    };

    $notifCount = fn () => Notifikasi::where('user_id', Auth::id())
        ->where('is_read', false)
        ->count();

    Route::get('dashboard', function (Request $request) use ($aktivitasQuery, $notifCount) {
        $pengguna = Auth::user();
        $namaPeran = $pengguna->role?->name;
        $tanggalHariIni = now()->locale('id')->translatedFormat('l, d F Y');

        if ($namaPeran === 'Pengelola Sarana & Prasarana') {
            $totalUnit = (int) Barang::sum('jumlah');
            $baik = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Baik'))->sum('jumlah');
            $ringan = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Rusak Ringan'))->sum('jumlah');
            $berat = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Rusak Berat'))->sum('jumlah');
            $pending = Pengadaan::whereIn('status', ['diajukan', 'menunggu_persetujuan'])->count();
            $tiketAktif = Pemeliharaan::whereIn('status', ['diajukan', 'dalam_pemeliharaan'])->count();
            $jumlahKategori = Barang::distinct('kategori_barang_id')->count('kategori_barang_id');

            $aset = Barang::with(['kategori', 'lokasi', 'kondisi', 'status'])
                ->when($request->filled('cari'), function ($q) use ($request) {
                    $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                    $q->where(fn ($w) => $w
                        ->where('kode_barang', 'like', $cari)
                        ->orWhere('nama_barang', 'like', $cari));
                })
                ->when($request->filled('kondisi'), function ($q) use ($request) {
                    $q->whereHas('kondisi', fn ($k) => $k->where('name', $request->input('kondisi')));
                })
                ->latest()
                ->paginate(5)
                ->withQueryString();

            return view('pages.pengelola.dashboard', [

                'menuAktif' => 'dashboard',
                'notifBelumDibaca' => $notifCount(),
                'tanggalHariIni' => $tanggalHariIni,
                'totalUnit' => $totalUnit,
                'baik' => $baik,
                'ringan' => $ringan,
                'berat' => $berat,
                'pending' => $pending,
                'tiketAktif' => $tiketAktif,
                'jumlahKategori' => $jumlahKategori,
                'aset' => $aset,
            ]);
        }

        if ($namaPeran === 'Pimpinan') {
            $totalUnit = (int) Barang::sum('jumlah');
            $valuasi = (int) Barang::selectRaw('COALESCE(SUM(harga_perolehan * jumlah), 0) as total')->value('total');
            $baik = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Baik'))->sum('jumlah');
            $ringan = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Rusak Ringan'))->sum('jumlah');
            $berat = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Rusak Berat'))->sum('jumlah');

            $pagu = 150000000;
            $realisasi = (int) DB::table('detail_pengadaan')
                ->join('pengadaan', 'pengadaan.id', '=', 'detail_pengadaan.pengadaan_id')
                ->where('pengadaan.status', 'disetujui')
                ->selectRaw('COALESCE(SUM(detail_pengadaan.subtotal), 0) as total')
                ->value('total');

            $antrean = Pengadaan::with(['user', 'detail', 'perencanaan.prioritas'])
                ->whereIn('status', ['diajukan', 'menunggu_persetujuan'])
                ->when($request->filled('prioritas'), function ($q) use ($request) {
                    $q->whereHas('perencanaan.prioritas', fn ($p) => $p->where('name', $request->input('prioritas')));
                })
                ->latest()
                ->take(6)
                ->get();
            $totalAntrean = Pengadaan::whereIn('status', ['diajukan', 'menunggu_persetujuan'])->count();
            $totalButuhRespon = (int) DB::table('detail_pengadaan')
                ->join('pengadaan', 'pengadaan.id', '=', 'detail_pengadaan.pengadaan_id')
                ->whereIn('pengadaan.status', ['diajukan', 'menunggu_persetujuan'])
                ->selectRaw('COALESCE(SUM(detail_pengadaan.subtotal), 0) as total')
                ->value('total');

            return view('pages.pimpinan.dashboard', [

                'menuAktif' => 'dashboard',
                'notifBelumDibaca' => $notifCount(),
                'tanggalHariIni' => $tanggalHariIni,
                'totalUnit' => $totalUnit,
                'valuasi' => $valuasi,
                'pagu' => $pagu,
                'realisasi' => $realisasi,
                'baik' => $baik,
                'ringan' => $ringan,
                'berat' => $berat,
                'antrean' => $antrean,
                'totalAntrean' => $totalAntrean,
                'totalButuhRespon' => $totalButuhRespon,
            ]);
        }

        // Administrator (default).
        $totalAktif = User::where('is_active', true)->count();
        $baruBulanIni = User::where('created_at', '>=', now()->subMonth())->count();
        $aktifMingguIni = User::where('is_active', true)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $roles = Role::orderBy('id')->get();

        $perPage = (int) $request->input('per_halaman', 6);
        $perPage = in_array($perPage, [6, 10, 25, 50], true) ? $perPage : 6;

        $aktivitas = $aktivitasQuery($request)->paginate($perPage)->withQueryString();

        $totalMaster = 0;
        foreach (['kategori_barang', 'satuan', 'lokasi', 'kondisi_barang', 'status_barang', 'jenis_pemeliharaan', 'jenis_pengadaan', 'prioritas', 'periode'] as $tabel) {
            $totalMaster += DB::table($tabel)->count();
        }

        return view('pages.admin.dashboard', [

            'menuAktif' => 'dashboard',
            'totalAktif' => $totalAktif,
            'baruBulanIni' => $baruBulanIni,
            'aktifMingguIni' => $aktifMingguIni,
            'roles' => $roles,
            'totalMaster' => $totalMaster,
            'aktivitas' => $aktivitas,
            'notifBelumDibaca' => $notifCount(),
            'tanggalHariIni' => $tanggalHariIni,
        ]);
    })->name('dashboard');

    Route::get('dashboard/ekspor', function (Request $request) use ($aktivitasQuery) {
        $baris = $aktivitasQuery($request)->get();

        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Waktu', 'Pengguna', 'Email', 'Role', 'Aktivitas', 'Deskripsi', 'Status']);

            foreach ($baris as $a) {
                fputcsv($out, [
                    $a->created_at?->locale('id')->translatedFormat('d M Y, H:i:s'),
                    $a->user?->name ?? 'Sistem',
                    $a->user?->email ?? '-',
                    $a->user?->role?->name ?? 'Sistem',
                    $a->activity,
                    $a->description,
                    $a->statusTampilan()['label'],
                ]);
            }

            fclose($out);
        }, 'aktivitas-sarpra-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    })->name('dashboard.ekspor');

    Route::get('dashboard/ekspor-eksekutif', function () {
        $baris = Pengadaan::with(['user', 'detail'])
            ->whereIn('status', ['diajukan', 'menunggu_persetujuan'])
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Nomor', 'Tanggal', 'Pengaju', 'Paket', 'Estimasi (Rp)']);

            foreach ($baris as $p) {
                fputcsv($out, [
                    $p->nomor_pengadaan,
                    $p->tanggal_pengajuan?->locale('id')->translatedFormat('d M Y'),
                    $p->user?->name ?? '-',
                    $p->detail->first()?->nama_barang ?? '-',
                    $p->detail->sum('subtotal'),
                ]);
            }

            fclose($out);
        }, 'laporan-eksekutif-sarpra-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    })->name('dashboard.ekspor-eksekutif');

    $putuskan = function (Request $request, Pengadaan $pengadaan, string $keputusan) {
        abort_unless(Auth::user()->role?->name === 'Pimpinan', 403);

        $pengadaan->update(['status' => $keputusan === 'disetujui' ? 'disetujui' : 'ditolak']);

        PersetujuanPengadaan::updateOrCreate(
            ['pengadaan_id' => $pengadaan->id],
            [
                'pimpinan_id' => Auth::id(),
                'status' => $keputusan === 'disetujui' ? 'disetujui' : 'ditolak',
                'catatan' => $request->input('catatan'),
                'tanggal_persetujuan' => now(),
            ]
        );

        RiwayatPengadaan::create([
            'pengadaan_id' => $pengadaan->id,
            'user_id' => Auth::id(),
            'status' => $pengadaan->status,
            'catatan' => $keputusan === 'disetujui'
                ? 'Disetujui pimpinan.'
                : 'Ditolak pimpinan: '.($request->input('catatan') ?? 'tanpa catatan.'),
        ]);

        return redirect()->route('dashboard')->with(
            'status',
            $keputusan === 'disetujui'
                ? "Usulan {$pengadaan->nomor_pengadaan} disetujui."
                : "Usulan {$pengadaan->nomor_pengadaan} ditolak."
        );
    };

    Route::post('dashboard/pengadaan/{pengadaan}/setujui', fn (Request $request, Pengadaan $pengadaan) => $putuskan($request, $pengadaan, 'disetujui'))->name('persetujuan.setujui');
    Route::post('dashboard/pengadaan/{pengadaan}/tolak', fn (Request $request, Pengadaan $pengadaan) => $putuskan($request, $pengadaan, 'ditolak'))->name('persetujuan.tolak');

    $hanya = function (string ...$roles): void {
        abort_unless(in_array(Auth::user()->role?->name, $roles, true), 403);
    };

    $shell = fn (string $aktif) => [

        'menuAktif' => $aktif,
        'notifBelumDibaca' => $notifCount(),
        'tanggalHariIni' => now()->locale('id')->translatedFormat('l, d F Y'),
    ];

    // ---- Administrator ----
    Route::get('admin/pengguna', function (Request $request) use ($shell, $hanya) {
        $hanya('Administrator');

        $pengguna = User::with('role')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                $q->where(fn ($w) => $w->where('name', 'like', $cari)->orWhere('email', 'like', $cari));
            })
            ->when($request->filled('role'), fn ($q) => $q->whereHas('role', fn ($r) => $r->where('name', $request->input('role'))))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pengguna.index', [
            ...$shell('pengguna'),
            'pengguna' => $pengguna,
            'roles' => Role::orderBy('id')->get(),
        ]);
    })->name('pengguna.index');

    Route::post('admin/pengguna/{user}/toggle', function (User $user) use ($hanya) {
        $hanya('Administrator');
        abort_if($user->id === Auth::id(), 403, 'Tidak dapat menonaktifkan akun sendiri.');

        $user->update(['is_active' => ! $user->is_active]);

        return redirect()->route('pengguna.index')->with(
            'status',
            $user->is_active ? "Akun {$user->name} diaktifkan." : "Akun {$user->name} dinonaktifkan."
        );
    })->name('pengguna.toggle');

    Route::get('admin/master', function (Request $request) use ($shell, $hanya) {
        $hanya('Administrator');

        $daftar = [
            'kategori' => ['label' => 'Kategori Barang', 'model' => KategoriBarang::class],
            'satuan' => ['label' => 'Satuan', 'model' => Satuan::class],
            'lokasi' => ['label' => 'Lokasi', 'model' => Lokasi::class],
            'kondisi' => ['label' => 'Kondisi Barang', 'model' => KondisiBarang::class],
            'status' => ['label' => 'Status Barang', 'model' => StatusBarang::class],
            'jenis-pemeliharaan' => ['label' => 'Jenis Pemeliharaan', 'model' => JenisPemeliharaan::class],
            'jenis-pengadaan' => ['label' => 'Jenis Pengadaan', 'model' => JenisPengadaan::class],
            'prioritas' => ['label' => 'Prioritas', 'model' => Prioritas::class],
            'periode' => ['label' => 'Periode', 'model' => Periode::class],
        ];

        $kunci = $request->input('entitas', 'kategori');
        $kunci = array_key_exists($kunci, $daftar) ? $kunci : 'kategori';

        $data = $daftar[$kunci]['model']::query()
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                $q->where('name', 'like', $cari);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.master.index', [
            ...$shell('master'),
            'daftar' => $daftar,
            'kunci' => $kunci,
            'data' => $data,
        ]);
    })->name('master.index');

    Route::get('admin/aktivitas', function (Request $request) use ($aktivitasQuery, $shell, $hanya) {
        $hanya('Administrator');

        $perPage = (int) $request->input('per_halaman', 10);
        $perPage = in_array($perPage, [6, 10, 25, 50], true) ? $perPage : 10;

        return view('pages.admin.aktivitas.index', [
            ...$shell('aktivitas'),
            'aktivitas' => $aktivitasQuery($request)->paginate($perPage)->withQueryString(),
            'roles' => Role::orderBy('id')->get(),
        ]);
    })->name('aktivitas.index');

    // ---- Pengelola ----
    Route::get('pengelolaan/perencanaan', function (Request $request) use ($shell, $hanya) {
        $hanya('Pengelola Sarana & Prasarana');

        $data = Perencanaan::with(['kategori', 'satuan', 'prioritas', 'periode', 'user'])
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                $q->where('nama_kebutuhan', 'like', $cari);
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengelola.perencanaan.index', [
            ...$shell('perencanaan'),
            'data' => $data,
        ]);
    })->name('perencanaan.index');

    Route::get('pengelolaan/pengadaan', function (Request $request) use ($shell, $hanya) {
        $hanya('Pengelola Sarana & Prasarana');

        $data = Pengadaan::with(['user', 'detail', 'perencanaan.prioritas'])
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                $q->where(fn ($w) => $w->where('nomor_pengadaan', 'like', $cari)->orWhere('alasan', 'like', $cari));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengelola.pengadaan.index', [
            ...$shell('pengadaan'),
            'data' => $data,
        ]);
    })->name('pengadaan.index');

    Route::get('pengelolaan/inventarisasi', function (Request $request) use ($shell, $hanya) {
        $hanya('Pengelola Sarana & Prasarana');

        $data = Barang::with(['kategori', 'lokasi', 'kondisi', 'status'])
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = '%'.str_replace(['%', '_'], '', (string) $request->input('cari')).'%';
                $q->where(fn ($w) => $w->where('kode_barang', 'like', $cari)->orWhere('nama_barang', 'like', $cari));
            })
            ->when($request->filled('kondisi'), fn ($q) => $q->whereHas('kondisi', fn ($k) => $k->where('name', $request->input('kondisi'))))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengelola.inventarisasi.index', [
            ...$shell('inventarisasi'),
            'data' => $data,
        ]);
    })->name('inventarisasi.index');

    Route::get('pengelolaan/pemeliharaan', function (Request $request) use ($shell, $hanya) {
        $hanya('Pengelola Sarana & Prasarana');

        $data = Pemeliharaan::with(['barang', 'user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengelola.pemeliharaan.index', [
            ...$shell('pemeliharaan'),
            'data' => $data,
        ]);
    })->name('pemeliharaan.index');

    // ---- Pimpinan ----
    Route::get('pimpinan/persetujuan', function (Request $request) use ($shell, $hanya) {
        $hanya('Pimpinan');

        $data = Pengadaan::with(['user', 'detail', 'perencanaan.prioritas', 'persetujuan'])
            ->whereIn('status', ['diajukan', 'menunggu_persetujuan', 'disetujui', 'ditolak'])
            ->when($request->filled('prioritas'), function ($q) use ($request) {
                $q->whereHas('perencanaan.prioritas', fn ($p) => $p->where('name', $request->input('prioritas')));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.pimpinan.persetujuan.index', [
            ...$shell('persetujuan'),
            'data' => $data,
        ]);
    })->name('persetujuan.index');

    Route::get('pimpinan/monitoring', function () use ($shell, $hanya) {
        $hanya('Pimpinan');

        $totalUnit = (int) Barang::sum('jumlah');
        $baik = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Baik'))->sum('jumlah');
        $ringan = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Rusak Ringan'))->sum('jumlah');
        $berat = (int) Barang::whereHas('kondisi', fn ($q) => $q->where('name', 'Rusak Berat'))->sum('jumlah');
        $valuasi = (int) Barang::selectRaw('COALESCE(SUM(harga_perolehan * jumlah), 0) as total')->value('total');

        $perKategori = KategoriBarang::withSum(['barangs as total_unit' => fn ($q) => $q->select(DB::raw('COALESCE(SUM(jumlah), 0)'))], 'jumlah')
            ->orderBy('name')
            ->get();

        return view('pages.pimpinan.monitoring.index', [
            ...$shell('monitoring'),
            'totalUnit' => $totalUnit,
            'baik' => $baik,
            'ringan' => $ringan,
            'berat' => $berat,
            'valuasi' => $valuasi,
            'perKategori' => $perKategori,
        ]);
    })->name('monitoring.index');

    Route::get('pimpinan/pelaporan', function () use ($shell, $hanya) {
        $hanya('Pimpinan');

        return view('pages.pimpinan.pelaporan.index', [
            ...$shell('pelaporan'),
            'ringkasan' => [
                'barang' => Barang::count(),
                'pengadaan' => Pengadaan::count(),
                'pemeliharaan' => Pemeliharaan::count(),
                'perencanaan' => Perencanaan::count(),
            ],
        ]);
    })->name('pelaporan.index');

    Route::get('laporan/barang', function () {
        abort_unless(in_array(Auth::user()->role?->name, ['Pimpinan', 'Pengelola Sarana & Prasarana'], true), 403);

        $baris = Barang::with(['kategori', 'lokasi', 'kondisi', 'status'])->latest()->get();

        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Kode', 'Nama', 'Kategori', 'Lokasi', 'Kondisi', 'Status', 'Jumlah', 'Harga (Rp)']);

            foreach ($baris as $b) {
                fputcsv($out, [
                    $b->kode_barang, $b->nama_barang,
                    $b->kategori?->name, $b->lokasi?->name,
                    $b->kondisi?->name, $b->status?->name,
                    $b->jumlah, $b->harga_perolehan,
                ]);
            }

            fclose($out);
        }, 'inventaris-sarpra-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    })->name('laporan.barang');
});

require __DIR__.'/settings.php';
