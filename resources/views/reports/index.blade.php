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

            {{-- Mapa --}}
            <div id="mapAll" class="rounded-lg border" style="height: 560px;"></div>

            {{-- CTA --}}
            <div class="mt-3 flex justify-end">
                <a href="{{ route('reports.create') }}"
                    class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-full shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Reportar nuevo
                </a>
            </div>

            {{-- Lista de reportes debajo del mapa --}}
            @if($reports->count())
            <div class="mt-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-700">Reportes recientes ({{ $reports->count() }})</h3>
                    <button id="locateMe" class="text-sm text-indigo-600 hover:underline">Centrar en mi ubicación</button>
                </div>

                <div id="reportList" class="bg-white rounded-lg border shadow divide-y max-h-72 overflow-y-auto">
                    @foreach($reports as $r)
                    <div>




                    </div>

                    <button
                        type="button"
                        class="w-full text-left p-3 hover:bg-indigo-50 focus:bg-indigo-50 focus:outline-none flex items-center justify-between"
                        data-report-id="{{ $r->id }}"
                        aria-label="Ver en mapa: {{ $r->title }}">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $r->title }}</p>
                            @if($r->description)
                            <p class="text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit($r->description, 120) }}
                            </p>
                            @endif
                            <p class="text-xs text-gray-500">{{ $r->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-indigo-400 text-xs">Ver en mapa →</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mt-4 bg-white rounded-lg border p-6 text-gray-500 text-center">
                No hay reportes todavía.
            </div>
            @endif
        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ----- MAPA -----
        const map = L.map('mapAll').setView([20.6736, -103.344], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const items = @json($reports);
        const markers = [];
        const markersById = {};

        items.forEach(r => {
            const lat = parseFloat(r.lat);
            const lng = parseFloat(r.lng);
            const desc = r.description ? String(r.description).replace(/</g,'&lt;').replace(/>/g,'&gt;') : '';
const html = `
  <strong>${r.title}</strong><br>
  <small>${(new Date(r.created_at)).toLocaleString()}</small>
  ${desc ? `<div style="margin-top:4px;color:#374151;font-size:12px;">${desc}</div>` : ''}
`;
const m = L.marker([lat, lng]).addTo(map).bindPopup(html);


            markers.push(m);
            markersById[r.id] = m;

            // Al hacer clic en el marcador, resaltar item de la lista
            m.on('click', () => highlightListItem(r.id));
        });

        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }

        // ----- LISTA <-> MAPA -----
        function highlightListItem(id) {
            const list = document.getElementById('reportList');
            if (!list) return;

            list.querySelectorAll('.is-active').forEach(el => {
                el.classList.remove('is-active', 'ring-2', 'ring-indigo-300', 'bg-indigo-50');
            });

            const el = list.querySelector(`[data-report-id="${id}"]`);
            if (el) {
                el.classList.add('is-active', 'ring-2', 'ring-indigo-300', 'bg-indigo-50');
                el.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        }

        // Clic en item: centrar y abrir popup
        document.querySelectorAll('[data-report-id]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-report-id');
                const m = markersById[id];
                if (m) {
                    map.setView(m.getLatLng(), 15, {
                        animate: true
                    });
                    m.openPopup();
                    highlightListItem(id);
                }
            });
        });

        // Centrar en mi ubicación
        const locateBtn = document.getElementById('locateMe');
        if (locateBtn && 'geolocation' in navigator) {
            locateBtn.addEventListener('click', () => {
                navigator.geolocation.getCurrentPosition(pos => {
                    const p = [pos.coords.latitude, pos.coords.longitude];
                    map.setView(p, 15);
                    L.circleMarker(p, {
                        radius: 6,
                        color: '#4f46e5'
                    }).addTo(map).bindPopup('Tu ubicación').openPopup();
                });
            });
        }
    </script>
</x-app-layout>