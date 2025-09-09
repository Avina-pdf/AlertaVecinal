<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#1E3A8A] tracking-tight flex items-center gap-2">
            <span class="bg-[#F3F4F6] text-[#1E3A8A] px-2 py-1 rounded">🗺</span>
            Mapa de repotes 
        </h2>
    </x-slot>

    <div class="py-6" style="background-color: #F3F4F6;">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
            <div class="bg-[#22C55E] text-white px-4 py-3 rounded-lg mb-4">
                {{ session('status') }}
            </div>
            @endif

            {{-- Mapa --}}
            <div id="mapAll" class="rounded-lg border" style="height: 560px; background-color: #FFFFFF;"></div>

            {{-- CTA --}}
            <div class="mt-3 flex justify-end">
                <a href="{{ route('reports.create') }}"
                    class="inline-flex items-center px-5 py-2 bg-[#1E3A8A] text-white font-semibold rounded-full shadow-lg hover:bg-[#1E3A8A] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#1E3A8A] focus:ring-offset-2">
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
                    <h3 class="font-semibold text-[#1E3A8A]">Reportes recientes ({{ $reports->count() }})</h3>
                    <button id="locateMe" class="text-sm text-[#1E3A8A] hover:underline">Centrar en mi ubicación</button>
                </div>

                <div id="reportList" class="bg-[#FFFFFF] rounded-lg border shadow divide-y max-h-72 overflow-y-auto">
                    @foreach($reports as $r)
                    <div></div>
                    <button
                        type="button"
                        class="w-full text-left p-3 hover:bg-[#F3F4F6] focus:bg-[#F3F4F6] focus:outline-none flex items-center justify-between"
                        data-report-id="{{ $r->id }}"
                        aria-label="Ver en mapa: {{ $r->title }}">
                        <div>
                            <p class="font-semibold text-[#1E3A8A]">{{ $r->title }}</p>
                            @if($r->description)
                            <p class="text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit($r->description, 120) }}
                            </p>
                            @endif
                            <p class="text-xs text-gray-500">{{ $r->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-[#1E3A8A] text-xs">Ver en mapa →</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mt-4 bg-[#FFFFFF] rounded-lg border p-6 text-gray-500 text-center">
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
                <strong style="color:#1E3A8A;">${r.title}</strong><br>
                <small style="color:#22C55E;">${(new Date(r.created_at)).toLocaleString()}</small>
                ${desc ? `<div style="margin-top:4px;color:#1E3A8A;font-size:12px;">${desc}</div>` : ''}
            `;
            const m = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map).bindPopup(html);

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
                el.classList.remove('is-active', 'ring-2', 'ring-[#1E3A8A]', 'bg-[#F3F4F6]');
            });

            const el = list.querySelector(`[data-report-id="${id}"]`);
            if (el) {
                el.classList.add('is-active', 'ring-2', 'ring-[#1E3A8A]', 'bg-[#F3F4F6]');
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
                        color: '#22C55E',
                        fillColor: '#22C55E',
                        fillOpacity: 0.8
                    }).addTo(map).bindPopup('Tu ubicación').openPopup();
                });
            });
        }
    </script>
</x-app-layout>