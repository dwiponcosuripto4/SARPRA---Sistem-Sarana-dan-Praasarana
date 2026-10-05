<?php

use App\Models\Notifikasi;
use App\Models\RiwayatAktivitas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        $pengguna = Auth::user();

        $totalPengguna = User::count();
        $totalAktif = User::where('is_active', true)->count();
        $aktivitasHariIni = RiwayatAktivitas::whereDate('created_at', today())->count();

        $aktivitasTerkini = RiwayatAktivitas::with(['user.role'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($a) => [
                'nama' => $a->user?->name ?? 'Sistem',
                'peran' => $a->user?->role?->name ?? 'Sistem',
                'modul' => $a->module ?? '-',
                'deskripsi' => $a->description ?? '-',
                'waktu' => $a->created_at?->locale('id')->diffForHumans() ?? '-',
            ]);

        // Segmen donut: keliling lingkaran r=60 → ±377.
        $keliling = 2 * pi() * 60;
        $warna = ['#022448', '#1e3a5f', '#3fb687'];
        $offset = 0;
        $peran = Role::withCount('users')->get()->values()->map(function ($r, $i) use ($totalPengguna, $keliling, $warna, &$offset) {
            $pct = $totalPengguna > 0 ? round($r->users_count / $totalPengguna * 100) : 0;
            $panjang = $totalPengguna > 0 ? round($r->users_count / $totalPengguna * $keliling) : 0;
            $segmen = ['panjang' => $panjang, 'offset' => -$offset, 'warna' => $warna[$i % count($warna)]];
            $offset += $panjang;

            return [
                'nama' => $r->name,
                'jumlah' => $r->users_count,
                'pct' => $pct,
                'segmen' => $segmen,
            ];
        });

        $notifBelumDibaca = Notifikasi::where('user_id', $pengguna->id)
            ->where('is_read', false)
            ->count();

        return view('dashboard', [
            'totalPengguna' => $totalPengguna,
            'totalAktif' => $totalAktif,
            'aktivitasHariIni' => $aktivitasHariIni,
            'aktivitasTerkini' => $aktivitasTerkini,
            'peran' => $peran,
            'notifBelumDibaca' => $notifBelumDibaca,
            'tanggalHariIni' => now()->locale('id')->translatedFormat('d F Y'),
        ]);
    })->name('dashboard');
});

require __DIR__.'/settings.php';
