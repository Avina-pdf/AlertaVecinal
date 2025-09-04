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
<body class="bg-gradient-to-br from-[#FDFDFC] to-[#dbdbd7] dark:from-[#0a0a0a] dark:to-[#161615] text-[#1b1b18] flex items-center justify-center min-h-screen">

    <div class="w-full max-w-4xl mx-auto bg-white dark:bg-[#161615] rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        <!-- Left: Info Section -->
        <div class="flex-1 p-10 flex flex-col justify-center bg-gradient-to-br from-[#F53003]/10 to-[#FF4433]/10 dark:from-[#1D0002] dark:to-[#3E3E3A]">
            <h1 class="text-4xl font-bold mb-4 text-[#F53003] dark:text-[#F61500]">Bienvenido a Alerta</h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-6">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, urna eu tincidunt consectetur, nisi nisl aliquam enim, vitae facilisis erat urna ut sapien. Suspendisse potenti. Etiam euismod, justo nec facilisis cursus, enim erat dictum erat, nec dictum enim erat nec enim.
            </p>
            <ul class="space-y-3 mb-8">
                <li class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#F53003] mr-3"></span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Notificaciones instantáneas y seguras</span>
                </li>
                <li class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#FF4433] mr-3"></span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Diseño moderno y fácil de usar</span>
                </li>
                <li class="flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-[#F53003] mr-3"></span>
                    <span class="text-[#1b1b18] dark:text-[#EDEDEC]">Protege tu información personal</span>
                </li>
            </ul>
            <div class="flex space-x-4">
                @if (Route::has('login'))
                    <a
                        href="{{ route('login') }}"
                        class="px-6 py-2 rounded-lg bg-gradient-to-r from-[#F53003] to-[#FF4433] text-white font-semibold shadow-lg hover:from-[#FF4433] hover:to-[#F53003] transition-all duration-300"
                    >
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="px-6 py-2 rounded-lg border border-[#F53003] text-[#F53003] dark:text-[#F61500] font-semibold shadow-lg bg-white dark:bg-[#161615] hover:bg-[#FDFDFC] dark:hover:bg-[#1D0002] transition-all duration-300"
                        >
                            Register
                        </a>
                    @endif
                @endif
            </div>
        </div>
        <!-- Right: Image/Visual Section -->
        <div class="flex-1 bg-gradient-to-br from-[#FF4433]/30 to-[#F53003]/30 dark:from-[#3E3E3A] dark:to-[#1D0002] flex items-center justify-center p-10">
            <div class="w-full h-80 flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80"
                     alt="Modern Alert"
                     class="rounded-2xl shadow-xl object-cover w-full h-full"
                />
            </div>
        </div>
    </div>

</body>
</html>
