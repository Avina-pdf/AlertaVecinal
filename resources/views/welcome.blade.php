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
<body class="min-h-screen flex items-center justify-center bg-blue-100 relative overflow-hidden">

    <!-- Decorative Blobs Background -->
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 opacity-30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-green-200 opacity-30 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 w-72 h-72 bg-blue-100 opacity-20 rounded-full blur-2xl -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl mx-auto bg-white/90 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row backdrop-blur-lg border border-blue-100">
        <!-- Left: Info Section -->
        <div class="flex-1 p-10 flex flex-col justify-center bg-blue-50/80">
            <h1 class="text-5xl font-extrabold mb-4 text-blue-900 drop-shadow-lg">Bienvenido a <span class="text-green-500">Alerta</span></h1>
            <p class="text-lg text-blue-800/80 mb-8">
                Conecta con tu comunidad, reporta lo que sucede y mantente informado al instante.
            </p>
            <ul class="space-y-4 mb-10">
                <li class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 text-xl shadow">🔔</span>
                    <span class="text-blue-900 font-medium">Notificaciones instantáneas y seguras</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 text-xl shadow">📱</span>
                    <span class="text-blue-900 font-medium">Diseño moderno y fácil de usar</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 text-xl shadow">🛡️</span>
                    <span class="text-blue-900 font-medium">Tu información siempre protegida</span>
                </li>
            </ul>
            <div class="flex space-x-4">
                @if (Route::has('login'))
                    <a
                        href="{{ route('login') }}"
                        class="px-8 py-3 rounded-lg bg-blue-700 text-white font-semibold shadow-lg hover:bg-blue-800 transition-all duration-300"
                    >
                        Iniciar sesión
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="px-8 py-3 rounded-lg border border-blue-700 text-blue-700 font-semibold shadow-lg bg-white hover:bg-blue-50 transition-all duration-300"
                        >
                            Registrarse
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <!-- Right: Illustration Section -->
        <div class="flex-1 flex items-center justify-center p-10 bg-blue-50/60">
            <div class="w-full h-80 flex items-center justify-center relative">
                <!-- Simple SVG City Illustration -->
                <svg viewBox="0 0 400 320" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                    <ellipse cx="200" cy="300" rx="180" ry="20" fill="#E0F2FE" />
                    <rect x="60" y="180" width="40" height="100" rx="8" fill="#38BDF8"/>
                    <rect x="120" y="140" width="50" height="140" rx="10" fill="#22C55E"/>
                    <rect x="190" y="110" width="60" height="170" rx="12" fill="#2563EB"/>
                    <rect x="270" y="170" width="40" height="110" rx="8" fill="#38BDF8"/>
                    <rect x="320" y="200" width="30" height="80" rx="6" fill="#22C55E"/>
                    <rect x="80" y="210" width="20" height="70" rx="4" fill="#2563EB"/>
                    <rect x="240" y="210" width="20" height="70" rx="4" fill="#22C55E"/>
                    <circle cx="200" cy="90" r="18" fill="#FACC15" opacity="0.8"/>
                    <g>
                        <rect x="170" y="250" width="20" height="30" rx="3" fill="#F1F5F9"/>
                        <rect x="210" y="250" width="20" height="30" rx="3" fill="#F1F5F9"/>
                    </g>
                </svg>
                <!-- Optional: Add a floating notification icon -->
                <div class="absolute top-8 right-8 animate-bounce">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-lg border-2 border-green-400 text-green-500 text-3xl">🔔</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
