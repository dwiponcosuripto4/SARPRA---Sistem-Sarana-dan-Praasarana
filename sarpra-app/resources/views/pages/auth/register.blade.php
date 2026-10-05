<x-layouts::auth.sarpra :title="__('Daftar Akun')">
    {{-- Header kartu ala Stitch --}}
    <div class="flex flex-col items-center text-center mb-6">
        <div class="flex justify-center mb-4">
            <x-sarpra-logo class="size-16 rounded-2xl shadow-sm" />
        </div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-[#d5e3fc]/60 rounded-full mb-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-[#1e3a5f]"></span>
            <span class="text-xs font-semibold tracking-wider uppercase text-[#57657a]">Sistem Pengelolaan Sarana &amp; Prasarana</span>
        </div>
        <h1 class="text-[28px] leading-9 font-semibold tracking-tight text-[#131b2e]">Daftar Akun Baru</h1>
        <p class="text-sm text-[#515f74] mt-0.5">Buat akun untuk mengakses SARPRA</p>
    </div>

    @if (session('status'))
        <div class="flex items-start gap-3 p-4 mb-6 bg-[#85f8c4]/25 rounded-lg relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#00422c]"></div>
            <span class="material-symbols-outlined text-[#00422c] text-xl shrink-0 mt-0.5">check_circle</span>
            <p class="text-sm text-[#002114]">{{ session('status') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="flex items-start gap-3 p-4 mb-6 bg-[#ffdad6]/40 rounded-lg relative overflow-hidden" id="validation-alert">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#ba1a1a]"></div>
            <span class="material-symbols-outlined text-[#ba1a1a] text-xl shrink-0 mt-0.5">shield_with_heart</span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[#93000a]">Data belum valid</p>
                <p class="text-xs text-[#93000a]/90 mt-0.5">{{ $errors->first() }}</p>
            </div>
            <button aria-label="Tutup notifikasi" class="text-[#93000a]/70 hover:text-[#93000a] p-0.5 transition-colors" onclick="document.getElementById('validation-alert').style.display='none'" type="button">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <div class="space-y-1">
            <label class="block text-sm font-medium text-[#131b2e]" for="name">
                Nama Lengkap <span class="text-[#ba1a1a]">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-[#74777f] text-xl pointer-events-none">badge</span>
                <input
                    class="w-full pl-11 pr-4 py-3 bg-white text-[#131b2e] placeholder:text-[#c4c6cf] text-sm rounded-lg shadow-sm border border-[#e2e7ff] focus:outline-none focus:border-[#1e3a5f] focus:ring-[3px] focus:ring-[#1e3a5f]/15 transition-all @error('name') border-[#ba1a1a] @enderror"
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>
            @error('name')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1">
            <label class="block text-sm font-medium text-[#131b2e]" for="email">
                Email <span class="text-[#ba1a1a]">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-[#74777f] text-xl pointer-events-none">mail</span>
                <input
                    class="w-full pl-11 pr-4 py-3 bg-white text-[#131b2e] placeholder:text-[#c4c6cf] text-sm rounded-lg shadow-sm border border-[#e2e7ff] focus:outline-none focus:border-[#1e3a5f] focus:ring-[3px] focus:ring-[#1e3a5f]/15 transition-all @error('email') border-[#ba1a1a] @enderror"
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="nama@instansi.go.id"
                    required
                    autocomplete="email"
                >
            </div>
            @error('email')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1">
            <label class="block text-sm font-medium text-[#131b2e]" for="password">
                Kata Sandi <span class="text-[#ba1a1a]">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-[#74777f] text-xl pointer-events-none">lock</span>
                <input
                    class="w-full pl-11 pr-11 py-3 bg-white text-[#131b2e] placeholder:text-[#c4c6cf] text-sm rounded-lg shadow-sm border border-[#e2e7ff] focus:outline-none focus:border-[#1e3a5f] focus:ring-[3px] focus:ring-[#1e3a5f]/15 transition-all @error('password') border-[#ba1a1a] @enderror"
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Minimal sesuai kebijakan kata sandi"
                    required
                    autocomplete="new-password"
                >
                <button aria-label="Tampilkan atau sembunyikan kata sandi" class="absolute right-4 text-[#74777f] hover:text-[#131b2e] transition-colors p-0.5 flex items-center" onclick="toggleSarpraPassword('password', 'password-icon')" type="button">
                    <span class="material-symbols-outlined text-xl" id="password-icon">visibility</span>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1">
            <label class="block text-sm font-medium text-[#131b2e]" for="password_confirmation">
                Konfirmasi Kata Sandi <span class="text-[#ba1a1a]">*</span>
            </label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-[#74777f] text-xl pointer-events-none">lock_reset</span>
                <input
                    class="w-full pl-11 pr-11 py-3 bg-white text-[#131b2e] placeholder:text-[#c4c6cf] text-sm rounded-lg shadow-sm border border-[#e2e7ff] focus:outline-none focus:border-[#1e3a5f] focus:ring-[3px] focus:ring-[#1e3a5f]/15 transition-all"
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    placeholder="Ulangi kata sandi Anda"
                    required
                    autocomplete="new-password"
                >
                <button aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi" class="absolute right-4 text-[#74777f] hover:text-[#131b2e] transition-colors p-0.5 flex items-center" onclick="toggleSarpraPassword('password_confirmation', 'password-confirmation-icon')" type="button">
                    <span class="material-symbols-outlined text-xl" id="password-confirmation-icon">visibility</span>
                </button>
            </div>
        </div>

        <div class="pt-1">
            <button class="w-full py-3.5 px-6 bg-[#1e3a5f] hover:bg-[#022448] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition-all duration-200 group active:scale-[0.99]" type="submit" data-test="register-user-button">
                <span>Daftar</span>
                <span class="material-symbols-outlined text-lg transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
            </button>
        </div>

        <p class="text-sm text-center text-[#515f74] pt-1">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-[#1e3a5f] hover:text-[#022448] hover:underline underline-offset-4">Masuk di sini</a>
        </p>
    </form>

    <script>
        function toggleSarpraPassword(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</x-layouts::auth.sarpra>
