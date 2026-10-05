<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="min-h-screen bg-[#faf8ff] text-[#131b2e] antialiased flex flex-col justify-between relative">
        <div class="pointer-events-none fixed inset-0 z-0" style="background: radial-gradient(ellipse 80% 80% at 50% -20%, rgba(30,58,95,0.06), rgba(250,248,255,0));"></div>
        <div class="pointer-events-none fixed inset-0 z-0 opacity-40" style="background-image: linear-gradient(to right, #eaedff 1px, transparent 1px), linear-gradient(to bottom, #eaedff 1px, transparent 1px); background-size: 4rem 4rem;"></div>

        <main class="relative z-10 flex-1 flex items-center justify-center w-full px-4 md:px-8 py-6">
            <div class="flex flex-col w-full items-center justify-center py-6">
                <div class="w-full max-w-[480px] bg-white rounded-xl shadow-xl p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#022448] to-[#1e3a5f]"></div>
                    {{ $slot }}
                </div>

                <div class="w-full max-w-[480px] mt-6 flex flex-col items-center gap-2 text-center">
                    <p class="text-xs text-[#515f74]/80">© 2026 SARPRA — Sistem Pengelolaan Sarana dan Prasarana</p>
                </div>
            </div>
        </main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
