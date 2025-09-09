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
<body class="bg-gradient-to-br from-[#FFFFFF] to-[#F3F4F6] text-[#1E3A8A] flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Animated 3D Map Background -->
    <div id="map-bg" class="absolute inset-0 z-0">
        <canvas id="three-map" style="width:100vw; height:100vh; display:block;"></canvas>
    </div>

    <div class="relative z-10 w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
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
        <!-- Right: 3D City Map Animation Section -->
        <div class="flex-1 bg-gradient-to-br from-[#22C55E]/30 to-[#1E3A8A]/30 flex items-center justify-center p-10">
            <div class="w-full h-80 flex items-center justify-center relative">
            <canvas id="three-city-map" class="rounded-2xl shadow-xl w-full h-full"></canvas>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/three@0.153.0/build/three.min.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('three-city-map');
            const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
            renderer.setSize(canvas.clientWidth, canvas.clientHeight, false);

            const scene = new THREE.Scene();
            scene.background = null;

            const camera = new THREE.PerspectiveCamera(45, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
            camera.position.set(0, 15, 25);
            camera.lookAt(0, 0, 0);

            // Ambient and directional light
            scene.add(new THREE.AmbientLight(0xffffff, 0.7));
            const dirLight = new THREE.DirectionalLight(0x1E3A8A, 0.7);
            dirLight.position.set(10, 20, 10);
            scene.add(dirLight);

            // Generate simple city blocks
            const blockSize = 2;
            const blockGap = 0.5;
            const rows = 5;
            const cols = 7;

            for (let x = -cols/2; x < cols/2; x++) {
                for (let z = -rows/2; z < rows/2; z++) {
                // Random building height
                const height = Math.random() * 6 + 2;
                const geometry = new THREE.BoxGeometry(blockSize, height, blockSize);
                const color = Math.random() > 0.5 ? 0x22C55E : 0x1E3A8A;
                const material = new THREE.MeshStandardMaterial({
                    color: color,
                    roughness: 0.6,
                    metalness: 0.2,
                });
                const building = new THREE.Mesh(geometry, material);
                building.position.set(
                    x * (blockSize + blockGap),
                    height / 2,
                    z * (blockSize + blockGap)
                );
                scene.add(building);
                }
            }

            // Simple ground plane
            const groundGeo = new THREE.PlaneGeometry(30, 20);
            const groundMat = new THREE.MeshStandardMaterial({ color: 0xF3F4F6 });
            const ground = new THREE.Mesh(groundGeo, groundMat);
            ground.rotation.x = -Math.PI / 2;
            ground.position.y = 0;
            scene.add(ground);

            // Animation loop (rotate camera around city)
            let angle = 0;
            function animate() {
                angle += 0.003;
                camera.position.x = Math.sin(angle) * 25;
                camera.position.z = Math.cos(angle) * 25;
                camera.lookAt(0, 0, 0);
                renderer.render(scene, camera);
                requestAnimationFrame(animate);
            }
            animate();

            // Responsive resize
            window.addEventListener('resize', () => {
                const width = canvas.clientWidth;
                const height = canvas.clientHeight;
                renderer.setSize(width, height, false);
                camera.aspect = width / height;
                camera.updateProjectionMatrix();
            });
            });
            </script>
        </div>
    </div>

    
    
</body>
</html>
