<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Alerta') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gradient-to-br from-[#FFFFFF] to-[#F3F4F6] text-[#1E3A8A] flex items-center justify-center min-h-screen">

    <div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        <!-- Left: Info Section -->
        <div class="flex-1 p-10 flex flex-col justify-center bg-gradient-to-br from-[#1E3A8A]/10 to-[#22C55E]/10">
            <h1 class="text-4xl font-bold mb-4 text-[#1E3A8A]">Bienvenido a Alerta</h1>
            <p class="text-lg text-[#1E3A8A]/80 mb-6">
                Conecta con tu comunidad, reporta lo que sucede y mantente informado al instante.
            </p>
            <ul class="space-y-3 mb-8">
                <li class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#22C55E] mr-3"></span>
                    <span class="text-[#1E3A8A]">🔔 Notificaciones instantáneas y seguras</span>
                </li>
                <li class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#1E3A8A] mr-3"></span>
                    <span class="text-[#1E3A8A]">📱 Diseño moderno y fácil de usar</span>
                </li>
                <li class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#22C55E] mr-3"></span>
                    <span class="text-[#1E3A8A]">🛡️ Tu información siempre protegida</span>
                </li>
            </ul>
            <div class="flex space-x-4">
                @if (Route::has('login'))
                    <a
                        href="{{ route('login') }}"
                        class="px-6 py-2 rounded-lg bg-gradient-to-r from-[#1E3A8A] to-[#22C55E] text-white font-semibold shadow-lg hover:from-[#22C55E] hover:to-[#1E3A8A] transition-all duration-300"
                    >
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="px-6 py-2 rounded-lg border border-[#1E3A8A] text-[#1E3A8A] font-semibold shadow-lg bg-white hover:bg-[#F3F4F6] transition-all duration-300"
                        >
                            Register
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <!-- Right: Image/Visual Section -->
        <div class="flex-1 bg-gradient-to-br from-[#22C55E]/30 to-[#1E3A8A]/30 flex items-center justify-center p-10">
            <div class="w-full h-80 flex items-center justify-center">
                <img src=""
                     alt="Modern Alert"
                     class="rounded-2xl shadow-xl object-cover w-full h-full"
                />
            </div>
        </div>
    </div>

</body>
</html>
