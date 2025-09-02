<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mapa de reportes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-50 text-green-800 px-4 py-3 rounded-lg mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <div id="mapAll" class="rounded-lg border" style="height: 560px;"></div>

            <div class="mt-3 flex justify-end">
                <a href="{{ route('reports.create') }}"
                   class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-full shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Reportar nuevo
                </a>
            </div>
        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('mapAll').setView([20.6736, -103.344], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const items = @json($reports);

        const markers = [];
        items.forEach(r => {
            const m = L.marker([parseFloat(r.lat), parseFloat(r.lng)])
                .addTo(map)
                .bindPopup(`<strong>${r.title}</strong><br><small>${(new Date(r.created_at)).toLocaleString()}</small>`);
            markers.push(m);
        });

        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
    </script>

    
</x-app-layout>
