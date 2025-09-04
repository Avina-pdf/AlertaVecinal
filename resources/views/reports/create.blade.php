<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo reporte</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc ms-5 text-sm">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mapa para elegir ubicación --}}
            <div id="mapPick" class="rounded-lg border" style="height: 420px;"></div>
            <p class="text-sm text-gray-500">Haz clic en el mapa para fijar la ubicación (puedes arrastrar el marcador).</p>

            {{-- Formulario --}}
            <form method="POST" action="{{ route('reports.store') }}" class="bg-white rounded-lg border p-4 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Título</label>
                    <input name="title" value="{{ old('title') }}" required
                           class="mt-1 w-full border rounded-lg px-3 py-2" maxlength="120">
                    @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                    <textarea name="description" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
                    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Latitud</label>
                        <input id="lat" name="lat" value="{{ old('lat') }}" required readonly
                               class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50">
                        @error('lat') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Longitud</label>
                        <input id="lng" name="lng" value="{{ old('lng') }}" required readonly
                               class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50">
                        @error('lng') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duración del reporte</label>
                        <select name="ttl" class="mt-1 w-full border rounded-lg px-3 py-2" required>
                            <option value="6h"  {{ old('ttl','24h')==='6h'?'selected':'' }}>6 horas</option>
                            <option value="24h" {{ old('ttl','24h')==='24h'?'selected':'' }}>24 horas</option>
                            <option value="3d"  {{ old('ttl')==='3d'?'selected':'' }}>3 días</option>
                            <option value="7d"  {{ old('ttl')==='7d'?'selected':'' }}>7 días</option>
                        </select>
                        @error('ttl') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('reports.map') }}" class="px-4 py-2 border rounded-lg">Cancelar</a>
                    <button id="submitBtn" class="px-5 py-2 bg-indigo-600 text-white rounded-lg disabled:opacity-50"
                            type="submit" disabled>Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    const map = L.map('mapPick').setView([20.6736, -103.344], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const submitBtn = document.getElementById('submitBtn');
    let marker = null;

    function updateInputs(lat, lng){
        latInput.value = Number(lat).toFixed(6);
        lngInput.value = Number(lng).toFixed(6);
        submitBtn.disabled = !(latInput.value && lngInput.value);
    }

    function setPoint(lat, lng){
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], {draggable:true}).addTo(map);
            marker.on('dragend', e => {
                const p = e.target.getLatLng();
                updateInputs(p.lat, p.lng);
            });
        }
        updateInputs(lat, lng);
        map.setView([lat, lng], 15);
    }

    // Click en mapa fija el punto
    map.on('click', e => setPoint(e.latlng.lat, e.latlng.lng));

    // Intentar centrar en mi ubicación y fijar marcador inicial
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => setPoint(pos.coords.latitude, pos.coords.longitude),
            ()  => {} // silencio si falla
        );
    }
    </script>
</x-app-layout> 