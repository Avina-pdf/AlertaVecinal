<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reportar incidente (selecciona el punto en el mapa)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-50 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white p-5 sm:p-6 rounded-xl shadow space-y-4">
                <form method="POST" action="{{ route('reports.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium">Título</label>
                            <input name="title" value="{{ old('title') }}" required
                                   class="w-full border rounded-lg px-3 py-2">
                            @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Descripción (opcional)</label>
                            <input name="description" value="{{ old('description') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                            @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- MAPA --}}
                    <div>
                        <label class="block text-sm font-medium mb-2">Selecciona la ubicación</label>
                        <div id="map" class="rounded-lg border" style="height: 480px;"></div>
                        <p class="text-xs text-gray-500 mt-2">
                            Haz clic en el mapa para colocar un marcador. Puedes arrastrarlo para afinar la posición.
                        </p>
                    </div>

                    {{-- Lat/Lng ocultos --}}
                    <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                    <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">

                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span id="coordsLabel">Sin punto seleccionado</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="useLocation"
                                    class="px-3 py-2 bg-gray-200 rounded-lg">Usar mi ubicación</button>
                            <button id="submitBtn"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                                    disabled>Guardar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="text-right">
                <a class="text-blue-700 hover:underline" href="{{ route('reports.map') }}">Ver mapa con reportes</a>
            </div>
        </div>
    </div>

    {{-- Leaflet (CDN) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([20.6736, -103.344], 13); // centrado inicial (GDL). Cámbialo si quieres.

        // Capa base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        const coordsLabel = document.getElementById('coordsLabel');
        const submitBtn = document.getElementById('submitBtn');
        let marker = null;

        function setPoint(lat, lng) {
            const ll = [lat, lng];
            if (marker) {
                marker.setLatLng(ll);
            } else {
                marker = L.marker(ll, { draggable: true }).addTo(map);
                marker.on('dragend', () => {
                    const p = marker.getLatLng();
                    latInput.value = p.lat.toFixed(7);
                    lngInput.value = p.lng.toFixed(7);
                    coordsLabel.textContent = `Lat: ${latInput.value}, Lng: ${lngInput.value}`;
                });
            }
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            coordsLabel.textContent = `Lat: ${latInput.value}, Lng: ${lngInput.value}`;
            submitBtn.disabled = false;
        }

        // Click en el mapa
        map.on('click', (e) => {
            setPoint(e.latlng.lat, e.latlng.lng);
        });

        // Intentar centrar en ubicación del usuario
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(pos => {
                map.setView([pos.coords.latitude, pos.coords.longitude], 15);
            });
        }

        // Botón "Usar mi ubicación" para poner marcador directo
        document.getElementById('useLocation').addEventListener('click', () => {
            if (!('geolocation' in navigator)) return;
            navigator.geolocation.getCurrentPosition(pos => {
                setPoint(pos.coords.latitude, pos.coords.longitude);
                map.setView([pos.coords.latitude, pos.coords.longitude], 16);
            });
        });
    </script>
</x-app-layout>
