<?php

use App\Models\Notifikasi;
use App\Models\RiwayatAktivitas;
use App\Models\Role;
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

    Route::get('dashboard', function (Request $request) use ($aktivitasQuery) {
        $pengguna = Auth::user();

        $totalAktif = User::where('is_active', true)->count();
        $baruBulanIni = User::where('created_at', '>=', now()->subMonth())->count();
        $aktifMingguIni = User::where('is_active', true)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        $roles = Role::orderBy('id')->get();

        $totalMaster = 0;
        foreach (['kategori_barang', 'satuan', 'lokasi', 'kondisi_barang', 'status_barang', 'jenis_pemeliharaan', 'jenis_pengadaan', 'prioritas', 'periode'] as $tabel) {
            $totalMaster += DB::table($tabel)->count();
        }

        $perPage = (int) $request->input('per_halaman', 6);
        $perPage = in_array($perPage, [6, 10, 25, 50], true) ? $perPage : 6;

        $aktivitas = $aktivitasQuery($request)->paginate($perPage)->withQueryString();

        $notifBelumDibaca = Notifikasi::where('user_id', $pengguna->id)
            ->where('is_read', false)
            ->count();

        return view('dashboard', [
            'totalAktif' => $totalAktif,
            'baruBulanIni' => $baruBulanIni,
            'aktifMingguIni' => $aktifMingguIni,
            'roles' => $roles,
            'totalMaster' => $totalMaster,
            'aktivitas' => $aktivitas,
            'notifBelumDibaca' => $notifBelumDibaca,
            'tanggalHariIni' => now()->locale('id')->translatedFormat('l, d F Y'),
        ]);
    })->name('dashboard');

    Route::get('dashboard/ekspor', function (Request $request) use ($aktivitasQuery) {
        $baris = $aktivitasQuery($request)->get();

        $namaFile = 'aktivitas-sarpra-'.now()->format('Ymd-His').'.csv';

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
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    })->name('dashboard.ekspor');
});

require __DIR__.'/settings.php';
