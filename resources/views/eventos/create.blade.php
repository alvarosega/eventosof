@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endsection

@section('title', 'Crear Evento')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Encabezado mejorado -->
    <div class="flex items-center gap-4 mb-8">
        <div class="p-3 bg-gradient-to-r from-primary/10 to-accentBlue/10 rounded-xl shadow-inner">
            <i class="fas fa-calendar-plus text-2xl text-transparent bg-clip-text bg-gradient-to-r from-primary to-accentBlue"></i>
        </div>
        <h2 class="text-3xl font-bold tracking-wide">
            <span class="bg-gradient-to-r from-primary to-accentBlue text-transparent bg-clip-text">
                Crear Nuevo Evento
            </span>
        </h2>
    </div>

    <!-- Formulario con mejor diseño -->
    <form action="{{ route('eventos.store') }}" method="POST" 
          class="bg-lightCard/90 dark:bg-darkCard/90 p-8 rounded-2xl shadow-xl border border-primary/20 dark:border-accentBlue/30">
        @csrf

        <!-- Nombre del Evento -->
        <div class="mb-6">
            <label for="nombre" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                <i class="fas fa-signature mr-2"></i> Nombre del Evento
            </label>
            <input type="text" id="nombre" name="nombre" required
                   class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                          focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
        </div>

        <!-- Fechas y horas en grid responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Fecha Inicio -->
            <div>
                <label for="fecha_inicio" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                    <i class="fas fa-calendar-day mr-2"></i> Fecha de Inicio
                </label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required
                       class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                              focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
            </div>
            
            <!-- Hora Inicio -->
            <div>
                <label for="hora_inicio" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                    <i class="fas fa-clock mr-2"></i> Hora de Inicio
                </label>
                <input type="time" id="hora_inicio" name="hora_inicio" required
                       class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                              focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
            </div>
            
            <!-- Fecha Finalización -->
            <div>
                <label for="fecha_finalizacion" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                    <i class="fas fa-calendar-day mr-2"></i> Fecha de Finalización
                </label>
                <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" required
                       class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                              focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
            </div>
            
            <!-- Hora Finalización -->
            <div>
                <label for="hora_finalizacion" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                    <i class="fas fa-clock mr-2"></i> Hora de Finalización
                </label>
                <input type="time" id="hora_finalizacion" name="hora_finalizacion" required
                       class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                              focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
            </div>
        </div>

        <!-- Estado -->
        <div class="mb-6">
            <label for="estado" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                <i class="fas fa-info-circle mr-2"></i> Estado
            </label>
            <select id="estado" name="estado" required
                    class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                           focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
                <option value="en espera" selected>En Espera</option>
                <option value="activo">Activo</option>
                <option value="finalizado">Finalizado</option>
            </select>
        </div>

        <!-- Descripción -->
        <div class="mb-6">
            <label for="descripcion" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                <i class="fas fa-align-left mr-2"></i> Descripción
            </label>
            <textarea id="descripcion" name="descripcion" rows="4"
                      class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                             focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200"></textarea>
        </div>

        <!-- Sección de ubicaciones -->
        <div class="mb-6 p-4 bg-primary/5 dark:bg-accentBlue/5 rounded-xl border border-primary/20 dark:border-accentBlue/30">
            <h3 class="text-lg font-semibold mb-4 text-primary dark:text-accentBlue">
                <i class="fas fa-map-marked-alt mr-2"></i> Configuración de Ubicaciones
            </h3>
            
            <!-- Cantidad de ubicaciones -->
            <div class="mb-4">
                <label for="cantidad_ubicaciones" class="block mb-3 text-sm font-semibold text-primary dark:text-accentBlue">
                    <i class="fas fa-hashtag mr-2"></i> Cantidad de Ubicaciones
                </label>
                <select id="cantidad_ubicaciones" name="cantidad_ubicaciones" required
                        class="w-full px-4 py-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm
                               focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all duration-200">
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }} ubicación{{ $i > 1 ? 'es' : '' }}</option>
                    @endfor
                </select>
            </div>

            <!-- Campo oculto para ubicaciones -->
            <input type="hidden" id="ubicacion" name="ubicacion">

            <!-- Botón de ubicación actual -->
            <button type="button" onclick="obtenerUbicacion()"
                    class="mb-4 bg-gradient-to-r from-primary to-accentBlue text-white px-4 py-3 rounded-xl shadow-md
                           hover:shadow-[0_0_15px_rgba(0,209,255,0.3)] transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-location-arrow"></i>
                <span>Usar mi ubicación actual</span>
            </button>

            <!-- Mapa -->
            <div id="mapa" class="w-full h-96 rounded-xl border border-primary/20 dark:border-accentBlue/30 shadow-sm"></div>
            
            <p class="mt-2 text-sm text-primary/70 dark:text-accentBlue/70">
                <i class="fas fa-info-circle mr-1"></i> Haz clic en el mapa para agregar ubicaciones
            </p>
        </div>

        <!-- Botón de envío -->
        <div class="text-right">
            <button type="submit"
                    class="bg-gradient-to-r from-success to-success/80 text-white px-6 py-3 rounded-xl shadow-lg
                           hover:shadow-[0_0_20px_rgba(0,240,168,0.3)] transition-all duration-300 hover:scale-[1.02] flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Crear Evento</span>
            </button>
        </div>
    </form>
</div>

<!-- Scripts (sin cambios en la funcionalidad) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Variables globales
    const zoomInicial = 6;
    const latitudInicial = -16.2902;
    const longitudInicial = -63.5887;

    // Inicializar el mapa
    let map = L.map('mapa').setView([latitudInicial, longitudInicial], zoomInicial);

    // Capa base (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Arreglo de coordenadas y marcadores
    let puntosSeleccionados = [];
    let marcadores = [];

    // Referencia al select de cantidad
    const selectCantidad = document.getElementById('cantidad_ubicaciones');

    // Actualizar campo hidden
    function actualizarCampoUbicacion() {
        const ubicacionesEnTexto = puntosSeleccionados
            .map(coords => coords.join(','))
            .join(';');
        document.getElementById('ubicacion').value = ubicacionesEnTexto;
    }

    // Colocar un marcador
    function colocarMarcador(lat, lng) {
        const indice = puntosSeleccionados.length - 1;
        const marker = L.marker([lat, lng])
            .addTo(map)
            .bindPopup(
                `Ubicación ${indice + 1}
                 <button type="button" onclick="borrarUbicacion(${indice})"
                         class="text-red-500 font-bold hover:text-red-700">X</button>`
            )
            .openPopup();
        marcadores.push(marker);
    }

    // Borrar ubicación
    function borrarUbicacion(indice) {
        map.removeLayer(marcadores[indice]);
        puntosSeleccionados.splice(indice, 1);
        marcadores.splice(indice, 1);
        actualizarCampoUbicacion();
        actualizarPopups();
    }

    // Actualizar popups
    function actualizarPopups() {
        for (let i = 0; i < marcadores.length; i++) {
            marcadores[i].unbindPopup();
            marcadores[i].bindPopup(
                `Ubicación ${i + 1}
                 <button type="button" onclick="borrarUbicacion(${i})"
                         class="text-red-500 font-bold hover:text-red-700">X</button>`
            );
        }
    }

    // Evento de clic en el mapa
    map.on('click', function(e) {
        const maxPuntos = parseInt(selectCantidad.value);

        if (puntosSeleccionados.length < maxPuntos) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            puntosSeleccionados.push([lat, lng]);
            colocarMarcador(lat, lng);
            actualizarCampoUbicacion();
        } else {
            alert(`Ya se alcanzó el máximo de ${maxPuntos} ubicaciones.`);
        }
    });

    // Obtener ubicación actual
    function obtenerUbicacion() {
        const maxPuntos = parseInt(selectCantidad.value);

        if (puntosSeleccionados.length >= maxPuntos) {
            alert(`Ya se alcanzó el máximo de ${maxPuntos} ubicaciones.`);
            return;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);

                    map.setView([lat, lng], 18);
                    puntosSeleccionados.push([lat, lng]);
                    colocarMarcador(lat, lng);
                    actualizarCampoUbicacion();
                },
                (error) => {
                    alert("Error al obtener la ubicación: " + error.message);
                },
                { enableHighAccuracy: true }
            );
        } else {
            alert("Tu navegador no soporta geolocalización.");
        }
    }

    // Resetear ubicaciones al cambiar cantidad
    selectCantidad.addEventListener('change', () => {
        marcadores.forEach(marker => map.removeLayer(marker));
        marcadores = [];
        puntosSeleccionados = [];
        actualizarCampoUbicacion();
    });

    // Asegurar que el mapa se renderice correctamente
    window.onload = function() {
        map.invalidateSize();
    };
</script>
@endsection