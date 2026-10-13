<x-layouts::app :title="__('Inventarisasi')" :notifBelumDibaca="$notifBelumDibaca" menuAktif="inventarisasi" judulHalaman="Inventarisasi">
    @php
        $kondisiBadge = [
            'Baik' => ['kelas' => 'bg-[#ecfdf5] text-[#047857]', 'dot' => 'bg-[#059669]'],
            'Rusak Ringan' => ['kelas' => 'bg-[#fffbeb] text-[#b45309]', 'dot' => 'bg-[#d97706]'],
            'Rusak Berat' => ['kelas' => 'bg-[#fef2f2] text-[#b91c1c]', 'dot' => 'bg-[#dc2626]'],
        ];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#e2e7ff]">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] tracking-tight">Inventarisasi</h1>
            <p class="text-sm text-[#515f74]">Seluruh data aset/barang tercatat.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-[#e2e7ff] px-3.5 py-2 rounded-lg shadow-xs text-xs font-medium text-[#515f74]">
            <span class="material-symbols-outlined text-lg text-[#1e3a5f]">calendar_today</span>
            <span>{{ $tanggalHariIni }}</span>
        </div>
    </div>

    <div class="bg-white border border-[#e2e7ff] rounded-xl shadow-xs overflow-hidden flex flex-col mt-6">
        <form method="GET" action="{{ route('inventarisasi.index') }}" class="p-5 border-b border-[#e2e7ff] flex flex-wrap items-center gap-2.5">
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-lg text-[#515f74] pointer-events-none">search</span>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode / nama..." class="w-60 h-9 pl-9 pr-3 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs text-[#0f172a] placeholder:text-[#94a3b8] focus:outline-none focus:border-[#1e3a5f]">
            </div>
            <div class="relative">
                <select name="kondisi" onchange="this.form.submit()" class="h-9 pl-3 pr-8 bg-[#f1f5f9] border border-[#e2e7ff] rounded-lg text-xs font-medium text-[#0f172a] focus:outline-none cursor-pointer appearance-none">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" @selected(request('kondisi') === 'Baik')>Baik</option>
                    <option value="Rusak Ringan" @selected(request('kondisi') === 'Rusak Ringan')>Rusak Ringan</option>
                    <option value="Rusak Berat" @selected(request('kondisi') === 'Rusak Berat')>Rusak Berat</option>
                </select>
                <span class="material-symbols-outlined absolute right-2 top-2 text-lg text-[#515f74] pointer-events-none">arrow_drop_down</span>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#f1f5f9] border-b border-[#e2e7ff] text-[#515f74] font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-3.5 px-5">Kode</th>
                        <th class="py-3.5 px-5">Nama Barang</th>
                        <th class="py-3.5 px-5">Kategori</th>
                        <th class="py-3.5 px-5">Lokasi</th>
                        <th class="py-3.5 px-5">Kondisi</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Jumlah</th>
                        <th class="py-3.5 px-5">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2e7ff] text-[#0f172a]">
                    @forelse ($data as $row)
                        @php $kb = $kondisiBadge[$row->kondisi?->name] ?? ['kelas' => 'bg-[#f1f5f9] text-[#515f74]', 'dot' => 'bg-[#94a3b8]']; @endphp
                        <tr class="hover:bg-[#f1f5f9]/60 transition-colors">
                            <td class="py-3.5 px-5 whitespace-nowrap font-semibold text-[#022448]" style="font-family: 'JetBrains Mono', monospace;">{{ $row->kode_barang }}</td>
                            <td class="py-3.5 px-5">
                                <div class="font-semibold">{{ $row->nama_barang }}</div>
                                <div class="text-[11px] text-[#515f74]">{{ $row->merk ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $row->kategori?->name ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">{{ $row->lokasi?->name ?? '-' }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-semibold text-[11px] {{ $kb['kelas'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $kb['dot'] }}"></span>{{ $row->kondisi?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-[#d5e3fc]/50 text-[#57657a] font-medium text-[11px]">{{ $row->status?->name ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">{{ $row->jumlah }}</td>
                            <td class="py-3.5 px-5 whitespace-nowrap" style="font-family: 'JetBrains Mono', monospace;">Rp {{ number_format($row->harga_perolehan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-5 text-center text-sm text-[#515f74]">Tidak ada data barang.</td>
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
