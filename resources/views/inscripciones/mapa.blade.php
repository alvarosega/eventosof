@extends('layouts.app')
@php
    /** @var \App\Models\Externo $externo */
    $externo = Auth::guard('externo')->user();
@endphp

@section('title', 'Inscripción al Evento')

@section('content')
<div class="space-y-6">
    <!-- Título con efecto gradiente -->
    <h2 class="text-3xl font-bold tracking-wide flex items-center gap-3">
        <span class="bg-gradient-to-r from-accentBlue to-accentPink text-transparent bg-clip-text">
            <i class="fas fa-map-marker-alt mr-2"></i>
            Selecciona tu ubicación
        </span>
        <span class="text-primary dark:text-accentBlue">para inscribirte a:</span>
        <span class="text-secondary dark:text-accentPink">{{ $evento->nombre }}</span>
    </h2>

    <!-- Mensaje de error -->
    @if (session('error'))
    <div class="p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center gap-2">
        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Detalles del Evento -->
    <div class="bg-lightCard/90 dark:bg-darkCard/90 rounded-xl shadow-lg border border-primary/20 dark:border-accentBlue/30 p-5 transition-all duration-300 hover:shadow-[0_0_15px_rgba(0,198,255,0.2)]">
        <h4 class="text-xl font-semibold mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle text-accentBlue"></i>
            <span class="bg-gradient-to-r from-accentBlue to-accentPink text-transparent bg-clip-text">Detalles del Evento</span>
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <p><strong class="text-primary dark:text-accentBlue">Nombre:</strong> {{ $evento->nombre }}</p>
            <p><strong class="text-primary dark:text-accentBlue">Fecha Inicio:</strong> {{ $evento->fecha_inicio }} - {{ $evento->hora_inicio }}</p>
            <p><strong class="text-primary dark:text-accentBlue">Fecha Finalización:</strong> {{ $evento->fecha_finalizacion }} - {{ $evento->hora_finalizacion }}</p>
            <p>
                <strong class="text-primary dark:text-accentBlue">Estado:</strong> 
                <span class="px-2.5 py-1 text-xs rounded-full font-medium 
                    {{ $evento->estado == 'activo' ? 'bg-success/10 text-success dark:text-success/90' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                    {{ $evento->estado }}
                </span>
            </p>
            <div class="md:col-span-2">
                <p><strong class="text-primary dark:text-accentBlue">Descripción:</strong> {{ $evento->descripcion }}</p>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div id="map" class="w-full h-[500px] rounded-xl border border-primary/20 dark:border-accentBlue/30 shadow-lg"></div>

    <!-- Formulario -->
    <form id="formularioRegistro" action="{{ route('inscripciones.storeUbicacion', $evento->id) }}" method="POST" enctype="multipart/form-data" 
          class="bg-lightCard/90 dark:bg-darkCard/90 rounded-xl shadow-lg border border-primary/20 dark:border-accentBlue/30 p-5 transition-all duration-300 hover:shadow-[0_0_15px_rgba(0,198,255,0.2)]">
        @csrf

        <!-- Campos ocultos para lat y lng -->
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">

        <!-- Campo: Foto de Referencia -->
        <div class="mb-6">
            <label for="foto_referencia" class="block mb-2 text-sm font-semibold text-primary dark:text-accentBlue">
                <i class="fas fa-camera mr-1"></i>
                Foto de Referencia
            </label>
            
            @if($externo && $externo->foto_referencia)
                <!-- Muestra la imagen actual si existe -->
                <div class="mb-4 flex flex-col items-start gap-3">
                    <img src="{{ asset('storage/externos_auth/' . $externo->foto_referencia) }}" 
                         alt="Foto de Referencia" 
                         class="max-w-xs rounded-lg shadow-md border border-primary/20 dark:border-accentBlue/30">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Foto actual (opcional subir nueva)</span>
                </div>
                <!-- Permite subir una nueva imagen de forma opcional -->
                <input type="file" name="foto_referencia" id="foto_referencia" accept="image/*"
                    class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/90 file:text-white hover:file:bg-primary/80 transition-all duration-200 file:shadow-sm file:hover:shadow-md" />
            @else
                <!-- Si no hay imagen previa, se obliga a subirla -->
                <input type="file" name="foto_referencia" id="foto_referencia" accept="image/*" required
                    class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/90 file:text-white hover:file:bg-primary/80 transition-all duration-200 file:shadow-sm file:hover:shadow-md" />
            @endif
        </div>

        <!-- Botones de acción -->
        <div class="flex flex-wrap gap-4">
            <!-- Botón ubicación real -->
            <button type="button" id="btn-ubicacion-real" 
                    class="bg-gradient-to-r from-primary to-accentBlue text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(0,209,255,0.4)] transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-location-arrow"></i>
                <span>Usar mi ubicación real</span>
            </button>

            <!-- Botón confirmar ubicación -->
            <button type="submit" 
                    class="bg-gradient-to-r from-success to-success/80 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(0,240,168,0.4)] transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-check"></i>
                <span>Confirmar Ubicación</span>
            </button>
        </div>
    </form>
</div>

<!-- Hoja de estilos de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<!-- Script principal de Leaflet -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Obtenemos la columna 'ubicacion' del evento, ejemplo: "-16.2902,-63.5887;-16.3000,-63.6000"
    const ubicacionEvento = "{{ $evento->ubicacion }}";
    const coordenadasEvento = ubicacionEvento.split(';').filter(c => c.trim() !== '');

    let coordenadasValidas = [];
    let eventoLat = 0;
    let eventoLng = 0;

    if (coordenadasEvento.length > 0) {
        coordenadasValidas = coordenadasEvento.map(coord => {
            const partes = coord.split(',').map(p => parseFloat(p.trim()));
            return { lat: partes[0], lng: partes[1] };
        }).filter(coord => !isNaN(coord.lat) && !isNaN(coord.lng));

        if (coordenadasValidas.length > 0) {
            eventoLat = coordenadasValidas[0].lat;
            eventoLng = coordenadasValidas[0].lng;
        }
    }

    if (coordenadasValidas.length === 0) {
        // Mostrar mensaje de error más elegante
        const errorDiv = document.createElement('div');
        errorDiv.className = 'p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center gap-2';
        errorDiv.innerHTML = `
            <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">El evento no tiene ubicaciones válidas configuradas.</span>
        `;
        document.querySelector('form').style.display = 'none';
        document.getElementById('map').replaceWith(errorDiv);
    } else {
        // Inicializar mapa con mejor estilo
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: true,
            doubleClickZoom: true,
            touchZoom: true
        }).setView([eventoLat, eventoLng], 14);

        // Capa base (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Icono personalizado para eventos
        const eventIcon = L.icon({
            iconUrl: '{{ Vite::asset("resources/images/imagenes/evento.png") }}',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });

        // Icono personalizado para usuario
        const userIcon = L.icon({
            iconUrl: '{{ Vite::asset("resources/images/markers/user-marker.png") }}',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Agregar marcadores del evento con mejor estilo
        coordenadasValidas.forEach((coord, index) => {
            L.marker([coord.lat, coord.lng], { 
                icon: eventIcon,
                riseOnHover: true
            }).bindPopup(`
                <div class="font-bold">{{ $evento->nombre }}</div>
                <div class="text-sm">Punto ${index + 1}</div>
                <div class="text-xs mt-1">${coord.lat.toFixed(6)}, ${coord.lng.toFixed(6)}</div>
            `).addTo(map);
        });

        // Marcador de usuario
        let userMarker = null;
        const updateHiddenFields = (lat, lng) => {
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
        };

        // Selección manual en el mapa con mejor feedback
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            
            if (userMarker) map.removeLayer(userMarker);
            
            userMarker = L.marker([lat, lng], {
                icon: userIcon,
                draggable: true,
                autoPan: true
            })
            .bindPopup(`
                <div class="font-bold">Tu ubicación seleccionada</div>
                <div class="text-sm">Lat: ${lat}</div>
                <div class="text-sm">Lng: ${lng}</div>
            `)
            .addTo(map)
            .openPopup();
            
            updateHiddenFields(lat, lng);
            
            // Permitir arrastrar el marcador
            userMarker.on('dragend', function(e) {
                const newLat = e.target.getLatLng().lat.toFixed(6);
                const newLng = e.target.getLatLng().lng.toFixed(6);
                updateHiddenFields(newLat, newLng);
                userMarker.setPopupContent(`
                    <div class="font-bold">Tu ubicación seleccionada</div>
                    <div class="text-sm">Lat: ${newLat}</div>
                    <div class="text-sm">Lng: ${newLng}</div>
                `);
            });
        });

        // Botón geolocalización mejorado
        document.getElementById('btn-ubicacion-real').addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert("Tu navegador no soporta geolocalización.");
                return;
            }
            
            // Feedback visual
            const btn = document.getElementById('btn-ubicacion-real');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detectando ubicación...';
            btn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude.toFixed(6);
                    const lng = pos.coords.longitude.toFixed(6);
                    
                    if (userMarker) map.removeLayer(userMarker);
                    
                    userMarker = L.marker([lat, lng], {
                        icon: userIcon,
                        draggable: true,
                        autoPan: true
                    })
                    .bindPopup(`
                        <div class="font-bold">Tu ubicación actual</div>
                        <div class="text-sm">Lat: ${lat}</div>
                        <div class="text-sm">Lng: ${lng}</div>
                    `)
                    .addTo(map)
                    .openPopup();
                    
                    map.setView([lat, lng], 16);
                    updateHiddenFields(lat, lng);
                    
                    // Restaurar botón
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    
                    // Permitir arrastrar el marcador
                    userMarker.on('dragend', function(e) {
                        const newLat = e.target.getLatLng().lat.toFixed(6);
                        const newLng = e.target.getLatLng().lng.toFixed(6);
                        updateHiddenFields(newLat, newLng);
                        userMarker.setPopupContent(`
                            <div class="font-bold">Tu ubicación seleccionada</div>
                            <div class="text-sm">Lat: ${newLat}</div>
                            <div class="text-sm">Lng: ${newLng}</div>
                        `);
                    });
                },
                (error) => {
                    // Restaurar botón
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    
                    alert("Error al obtener tu ubicación. Selecciónala manualmente en el mapa.");
                },
                { 
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        // Inicializar campos ocultos
        updateHiddenFields('', '');

        // Agregar verificación al envío del formulario con mejor feedback
        const formulario = document.getElementById('formularioRegistro');
        formulario.addEventListener('submit', function(e) {
            const lat = document.getElementById('lat').value;
            const lng = document.getElementById('lng').value;
            
            if (!lat || !lng) {
                e.preventDefault();
                
                // Mostrar mensaje de error elegante
                const errorDiv = document.createElement('div');
                errorDiv.className = 'p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center gap-2 mb-4';
                errorDiv.innerHTML = `
                    <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Por favor, selecciona tu ubicación haciendo clic en el mapa o usando 'Usar mi ubicación real'.</span>
                `;
                
                // Insertar antes del formulario
                formulario.parentNode.insertBefore(errorDiv, formulario);
                
                // Hacer scroll al mensaje
                errorDiv.scrollIntoView({ behavior: 'smooth' });
                
                return false;
            }
        });
    }
</script>
@endsection