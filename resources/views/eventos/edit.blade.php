@extends('layouts.app')

@section('title', 'Editar Evento Tipo 1')

@section('content')
    {{-- Título con el estilo del primer código --}}
    <div class="flex items-center gap-4 mb-8 p-4 bg-lightCard dark:bg-darkCard rounded-xl shadow-lg border border-primary/20 dark:border-accentBlue/30">
        <div class="p-3 bg-gradient-to-r from-primary to-accentBlue text-white rounded-xl shadow-md">
            <i class="fas fa-calendar-edit text-lg"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-wide">Editar Evento Tipo 1</h1>
        </div>
    </div>

    {{-- Contenedor del formulario con el estilo del dashboard --}}
    <div class="bg-lightCard/90 dark:bg-darkCard/90 rounded-xl shadow-2xl mb-8 border border-primary/20 dark:border-accentBlue/30 overflow-hidden">
        {{-- Encabezado del formulario --}}
        <div class="bg-gradient-to-r from-primary to-secondary p-4 border-b border-accentBlue/20">
            <h3 class="text-xl font-semibold tracking-wide flex items-center gap-2">
                <i class="fas fa-edit text-accentPink"></i>
                <span>Formulario de Edición</span>
            </h3>
        </div>

        {{-- Formulario ORIGINAL (solo cambié los estilos de las clases) --}}
        <form action="{{ route('eventos.update', $evento->id) }}" method="POST" class="p-5">
            @csrf
            @method('PUT')

            {{-- Nombre del Evento --}}
            <div class="mb-4">
                <label for="nombre" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Nombre del Evento:</label>
                <input type="text" name="nombre" id="nombre" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard"
                       value="{{ old('nombre', $evento->nombre) }}">
                @error('nombre')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de Inicio --}}
            <div class="mb-4">
                <label for="fecha_inicio" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard"
                       value="{{ old('fecha_inicio', $evento->fecha_inicio) }}">
                @error('fecha_inicio')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hora de Inicio --}}
            <div class="mb-4">
                <label for="hora_inicio" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Hora de Inicio:</label>
                <input type="time" name="hora_inicio" id="hora_inicio" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard"
                       value="{{ old('hora_inicio', $evento->hora_inicio) }}">
                @error('hora_inicio')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de Finalización --}}
            <div class="mb-4">
                <label for="fecha_finalizacion" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Fecha de Finalización:</label>
                <input type="date" name="fecha_finalizacion" id="fecha_finalizacion" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard"
                       value="{{ old('fecha_finalizacion', $evento->fecha_finalizacion) }}">
                @error('fecha_finalizacion')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hora de Finalización --}}
            <div class="mb-4">
                <label for="hora_finalizacion" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Hora de Finalización:</label>
                <input type="time" name="hora_finalizacion" id="hora_finalizacion" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard"
                       value="{{ old('hora_finalizacion', $evento->hora_finalizacion) }}">
                @error('hora_finalizacion')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label for="descripcion" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard" rows="4">{{ old('descripcion', $evento->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado --}}
            <div class="mb-4">
                <label for="estado" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Estado:</label>
                <select name="estado" id="estado" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard dark:bg-darkCard">
                    <option value="activo" {{ old('estado', $evento->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="en espera" {{ old('estado', $evento->estado) == 'en espera' ? 'selected' : '' }}>En espera</option>
                    <option value="finalizado" {{ old('estado', $evento->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
                @error('estado')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ubicación --}}
            <div class="mb-4">
                <label for="ubicacion" class="block font-semibold mb-1 text-primary dark:text-accentBlue">Ubicación (lat,lng):</label>
                <input type="text" name="ubicacion" id="ubicacion" class="w-full p-2 border border-primary/20 dark:border-accentBlue/30 rounded-lg bg-lightCard/50 dark:bg-darkCard/50" readonly
                       value="{{ old('ubicacion', $evento->ubicacion) }}">
                <small class="text-gray-500 dark:text-gray-400 text-sm">Haz clic en el mapa para seleccionar una nueva ubicación.</small>
                @error('ubicacion')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror

                {{-- Mapa con estilos del dashboard --}}
                <div id="map" class="w-full h-64 mt-3 rounded-xl border border-primary/20 dark:border-accentBlue/30"></div>
            </div>

            {{-- Botón con el estilo del dashboard --}}
            <button type="submit" class="bg-gradient-to-r from-primary to-accentBlue text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(0,209,255,0.3)] transition-all duration-300">
                Actualizar Evento
            </button>
        </form>
    </div>

    {{-- Scripts ORIGINALES (sin cambios) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Obtener coordenadas iniciales
            let ubicacionInput = document.getElementById("ubicacion");
            let ubicacion = ubicacionInput.value || "-16.5,-68.1"; // valor por defecto

            let [lat, lng] = ubicacion.split(",").map(parseFloat);
            let map = L.map("map").setView([lat, lng], 13);

            // Añadir capa base (mapa)
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Marcador inicial
            let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            // Al hacer clic en el mapa, actualizar marcador y valor en input
            map.on("click", function (e) {
                let { lat, lng } = e.latlng;
                marker.setLatLng([lat, lng]);
                ubicacionInput.value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
            });

            // También actualiza input cuando se arrastra el marcador
            marker.on("dragend", function (e) {
                let { lat, lng } = marker.getLatLng();
                ubicacionInput.value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
            });
        });
    </script>
@endsection