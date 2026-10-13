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
    <body class="min-h-screen bg-[#f8fafc] text-[#131b2e] antialiased">
        @include('layouts.app.sidebar')

        <div class="lg:pl-64">
            @include('layouts.app.header')

            <main class="px-4 md:px-8 py-6 md:py-8 min-h-[calc(100vh-4rem)]">
                {{ $slot }}
            </main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts

        <script>
            // Halaman SARPRA selalu terang: paksa appearance Flux ke light
            // agar menu/dropdown tidak mengikuti dark-mode OS.
            if (window.Flux) {
                window.Flux.applyAppearance('light');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </body>
</html>
