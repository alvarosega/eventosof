@extends('layouts.app')

@section('title', 'Pantalla Principal')

@section('content')
  {{-- Mensajes --}}
  @if (session('success'))
    <div class="mb-6 p-4 bg-success/10 border-l-4 border-success text-success dark:text-success/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center">
      <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      <span class="font-medium">{{ session('success') }}</span>
    </div>
  @endif

  @if (session('error'))
    <div class="mb-6 p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center">
      <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
      </svg>
      <span class="font-medium">{{ session('error') }}</span>
    </div>
  @endif

  {{-- Título de Bienvenida --}}
  <div class="flex items-center gap-4 mb-8 p-4 bg-lightCard dark:bg-darkCard rounded-xl shadow-lg border border-primary/20 dark:border-accentBlue/30">
    <div class="p-3 bg-gradient-to-r from-primary to-accentBlue text-white rounded-xl shadow-md">
      <i class="fas fa-user-check text-lg"></i>
    </div>
    <div>
      <h2 class="text-2xl font-bold tracking-wide">
        Bienvenido, {{ $usuario->nombre_completo ?? $usuario->nombre }}
      </h2>
      <span class="text-sm font-medium text-transparent bg-clip-text bg-gradient-to-r from-accentBlue to-accentPink">
        ({{ $usuario->rol }})
      </span>
    </div>
  </div>

  @if($usuario->rol == 'externo')
  {{-- Sección Externo --}}
  <div class="bg-lightCard/90 dark:bg-darkCard/90 rounded-xl shadow-2xl mb-8 border border-primary/20 dark:border-accentBlue/30 overflow-hidden transition-all duration-300 hover:shadow-[0_0_20px_rgba(0,198,255,0.2)]">
    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-primary to-secondary p-4 border-b border-accentBlue/20">
      <h3 class="text-xl font-semibold tracking-wide flex items-center gap-2">
        @if($usuario->evento_id)
          <i class="fas fa-calendar-day text-accentPink"></i> 
          <span>Mi Evento Actual</span>
        @else
          <i class="fas fa-calendar-alt text-accentBlue"></i> 
          <span>Eventos Disponibles</span>
        @endif
      </h3>
    </div>

    <!-- Contenido -->
    <div class="p-5">
      @if($usuario->evento_id)
        @php $miEvento = $eventos->firstWhere('id', $usuario->evento_id); @endphp

        @if($miEvento)
          <!-- Botones de Acción -->
          <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('pedidos.create', $miEvento->id) }}" 
               class="bg-gradient-to-r from-success to-success/80 hover:from-success/90 hover:to-success text-white px-4 py-2.5 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(0,240,168,0.3)] transition-all duration-300 flex items-center gap-2">
              <i class="fas fa-cart-plus"></i>
              <span>Nuevo Pedido</span>
            </a>
            <a href="{{ route('inscripciones.cancelForm', $miEvento->id) }}" 
               class="bg-gradient-to-r from-danger/90 to-danger/80 hover:from-danger hover:to-danger/90 text-white px-4 py-2.5 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(255,42,109,0.3)] transition-all duration-300 flex items-center gap-2">
              <i class="fas fa-times-circle"></i>
              <span>Cancelar Inscripción</span>
            </a>
          </div>

          <!-- Sección de Pedidos -->
          <div class="mb-8 bg-lightCard dark:bg-darkCard p-4 rounded-xl border border-primary/20 dark:border-accentBlue/30">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2">
              <i class="fas fa-clipboard-list text-accentBlue"></i> 
              <span>Mis Pedidos</span>
            </h4>
            
            @if($pedidos->isEmpty())
              <p class="text-center py-4 text-gray-500 dark:text-gray-400">No tienes pedidos registrados.</p>
            @else
              <div class="overflow-x-auto rounded-xl border border-primary/20 dark:border-accentBlue/30">
                <table class="min-w-full">
                  <thead class="bg-gradient-to-r from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20">
                    <tr>
                      <th class="px-5 py-3 text-left text-sm font-semibold text-primary dark:text-accentBlue">ID</th>
                      <th class="px-5 py-3 text-left text-sm font-semibold text-primary dark:text-accentBlue">Cantidad</th>
                      <th class="px-5 py-3 text-left text-sm font-semibold text-primary dark:text-accentBlue">Total</th>
                      <th class="px-5 py-3 text-left text-sm font-semibold text-primary dark:text-accentBlue">Fecha</th>
                      <th class="px-5 py-3 text-left text-sm font-semibold text-primary dark:text-accentBlue">Estado</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-primary/10 dark:divide-accentBlue/20">
                    @foreach($pedidos as $pedido)
                      <tr class="hover:bg-primary/5 dark:hover:bg-accentBlue/5 transition-colors duration-200">
                        <td class="px-5 py-3">{{ $pedido->id }}</td>
                        <td class="px-5 py-3">{{ $pedido->cantidad }}</td>
                        <td class="px-5 py-3">{{ number_format($pedido->total, 2) }}</td>
                        <td class="px-5 py-3">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3">
                          @if ($pedido->estado == 'pendiente')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning dark:text-warning/90">Pendiente</span>
                          @elseif ($pedido->estado == 'en_preparacion')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-info/10 text-info dark:text-info/90">En Preparación</span>
                          @elseif ($pedido->estado == 'enviado')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300">Enviado</span>
                          @elseif ($pedido->estado == 'entregado')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-success/10 text-success dark:text-success/90">Entregado</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>

          <!-- Mapa del Evento -->
          <div class="mb-4">
            <h4 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fas fa-map-marked-alt text-accentPink"></i>
              <span>Ubicación</span>
            </h4>
            <div id="map" class="w-full h-80 rounded-xl border border-primary/20 dark:border-accentBlue/30"></div>
          </div>

        @else
          <!-- Mensaje de evento no disponible -->
          <div class="p-4 bg-warning/10 border-l-4 border-warning text-warning dark:text-warning/80 rounded-lg flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>Tu evento registrado no está disponible.</span>
          </div>
        @endif
      @else
        {{-- Eventos disponibles --}}
        @if($eventos->isEmpty())
          <div class="p-4 bg-info/10 border-l-4 border-info text-info dark:text-info/80 rounded-lg flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <span>No hay eventos disponibles.</span>
          </div>
        @else
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($eventos as $evento)
              @if($evento->estado != 'finalizado')
                <div class="bg-lightCard dark:bg-darkCard rounded-xl overflow-hidden shadow-md border border-primary/20 dark:border-accentBlue/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                  <!-- Encabezado de la tarjeta -->
                  <div class="p-4 border-b border-primary/20 dark:border-accentBlue/30">
                    <h5 class="font-bold text-lg">{{ $evento->nombre }}</h5>
                    <small class="text-sm text-primary/70 dark:text-accentBlue/70">ID: {{ $evento->id }}</small>
                  </div>
                  
                  <!-- Cuerpo de la tarjeta -->
                  <div class="p-4">
                    <p class="mb-2 flex items-center gap-2">
                      <span class="font-medium">Estado:</span>
                      <span class="px-2.5 py-1 text-xs rounded-full font-medium 
                        {{ $evento->estado == 'activo' ? 'bg-success/10 text-success dark:text-success/90' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $evento->estado }}
                      </span>
                    </p>
                    <p class="mb-2"><span class="font-medium">Inicio:</span> {{ $evento->fecha_inicio }} {{ $evento->hora_inicio }}</p>
                    <p class="mb-3"><span class="font-medium">Fin:</span> {{ $evento->fecha_finalizacion }} {{ $evento->hora_finalizacion }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($evento->descripcion, 100) }}</p>
                  </div>
                  
                  <!-- Pie de la tarjeta -->
                  <div class="p-4 border-t border-primary/20 dark:border-accentBlue/30 text-center">
                    <a href="{{ route('inscripciones.showMapa', $evento->id) }}" 
                       class="bg-gradient-to-r from-primary to-accentBlue text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(0,209,255,0.3)] transition-all duration-300 inline-flex items-center gap-2">
                      <i class="fas fa-map-marker-alt"></i>
                      <span>Inscribirme</span>
                    </a>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        @endif
      @endif
    </div>
  </div>
@else
  {{-- Sección Admin --}}
  <div class="bg-lightCard/90 dark:bg-darkCard/90 rounded-xl shadow-2xl mb-8 border border-primary/20 dark:border-accentBlue/30 overflow-hidden transition-all duration-300 hover:shadow-[0_0_20px_rgba(0,198,255,0.2)]">
    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-primary to-secondary p-4 border-b border-accentBlue/20 flex flex-wrap justify-between items-center gap-4">
      <div class="flex items-center gap-3">
        <i class="fas fa-tools text-accentPink"></i>
        <h3 class="font-semibold tracking-wide">Gestión de Eventos</h3>
      </div>
      
      @if($usuario->rol == 'superadmin')
        <div class="flex flex-wrap gap-3">
          <a href="{{ route('eventos.create') }}" 
             class="bg-gradient-to-r from-accentPink to-accentPink/80 hover:from-accentPink hover:to-accentPink/90 text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(255,45,247,0.3)] transition-all duration-300 flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Nuevo Evento</span>
          </a>
          <a href="{{ route('catalogos.index') }}" 
             class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(0,0,0,0.2)] transition-all duration-300 flex items-center gap-2">
            <i class="fas fa-book"></i>
            <span>Catálogos</span>
          </a>
        </div>
      @endif
    </div>

    <!-- Contenido -->
    <div class="p-5">
      @if($eventos->isEmpty())
        <div class="p-4 bg-info/10 border-l-4 border-info text-info dark:text-info/80 rounded-lg flex items-center gap-2">
          <i class="fas fa-info-circle"></i>
          <span>No hay eventos registrados</span>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          @foreach($eventos as $evento)
            <div class="bg-lightCard dark:bg-darkCard rounded-xl overflow-hidden shadow-md border border-primary/20 dark:border-accentBlue/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
              <!-- Encabezado de la tarjeta -->
              <div class="p-4 border-b border-primary/20 dark:border-accentBlue/30">
                <h5 class="font-bold text-lg">{{ $evento->nombre }}</h5>
                <small class="text-sm text-primary/70 dark:text-accentBlue/70">ID: {{ $evento->id }}</small>
              </div>
              
              <!-- Cuerpo de la tarjeta -->
              <div class="p-4">
                <p class="mb-2 flex items-center gap-2">
                  <span class="font-medium">Estado:</span>
                  <span class="px-2.5 py-1 text-xs rounded-full font-medium 
                    {{ $evento->estado == 'activo' ? 'bg-success/10 text-success dark:text-success/90' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                    {{ $evento->estado }}
                  </span>
                </p>
                <p class="mb-2"><span class="font-medium">Inicio:</span> {{ $evento->fecha_inicio }} {{ $evento->hora_inicio }}</p>
                <p class="mb-2"><span class="font-medium">Fin:</span> {{ $evento->fecha_finalizacion }} {{ $evento->hora_finalizacion }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-3">
                  {{ Str::limit($evento->descripcion, 100) }}
                </p>
              </div>
              
              <!-- Pie de la tarjeta -->
              <div class="p-4 border-t border-primary/20 dark:border-accentBlue/30">
                <div class="flex flex-wrap justify-between items-center gap-3 mb-3">
                  <a href="{{ route('catalogos.show', $evento->id) }}" 
                     class="bg-gradient-to-r from-primary to-accentBlue text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_10px_rgba(0,209,255,0.3)] transition-all duration-300 flex-1 text-center">
                    <i class="fas fa-box-open mr-1"></i>
                    <span>Catálogo</span>
                  </a>
                  
                  @if($usuario->rol == 'superadmin')
                    <div class="flex gap-3">
                      <a href="{{ route('eventos.edit', $evento->id) }}" 
                         class="text-gray-700 dark:text-gray-300 hover:text-accentBlue transition-colors duration-200 p-2">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-gray-700 dark:text-gray-300 hover:text-danger transition-colors duration-200 p-2"
                                onclick="return confirm('¿Eliminar este evento?')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  @endif
                </div>
                
                <a href="{{ route('pedidos.evento', $evento->id) }}" 
                   class="block bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300 text-center">
                  <i class="fas fa-clipboard-list mr-1"></i>
                  <span>Ver Pedidos</span>
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endif

@if($usuario->rol != 'externo')
<!-- Mapa de Eventos para Admin -->
<div class="bg-lightCard/90 dark:bg-darkCard/90 p-5 rounded-xl shadow-2xl border border-primary/20 dark:border-accentBlue/30 mb-8">
  <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
    <i class="fas fa-map-marked-alt text-accentPink"></i>
    <span>Mapa de Eventos</span>
  </h3>
  <div id="adminMap" class="w-full h-96 rounded-xl border border-primary/20 dark:border-accentBlue/30"></div>
</div>
@endif

<!-- Scripts para Mapas -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  @if($usuario->rol == 'externo' && $usuario->evento_id && isset($miEvento))
    const ubicacionEvento = "{{ $miEvento->ubicacion }}";
    const coordenadasEvento = ubicacionEvento.split(';').filter(c => c.trim() !== '');
    let coordenadasValidas = [];
    
    if (coordenadasEvento.length > 0) {
      coordenadasValidas = coordenadasEvento.map(coord => {
        const partes = coord.split(',').map(p => parseFloat(p.trim()));
        return { lat: partes[0], lng: partes[1] };
      }).filter(coord => !isNaN(coord.lat) && !isNaN(coord.lng));
    }

    if (coordenadasValidas.length > 0) {
      const map = L.map('map').setView([coordenadasValidas[0].lat, coordenadasValidas[0].lng], 14);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);
      
      const eventIcon = L.icon({
        iconUrl: '{{ Vite::asset("resources/images/imagenes/evento.png") }}',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
      });
      
      coordenadasValidas.forEach((coord, index) => {
        L.marker([coord.lat, coord.lng], { icon: eventIcon })
          .bindPopup(`
            <div class="font-bold">{{ $miEvento->nombre }}</div>
            <div class="text-sm">Punto ${index + 1}</div>
          `)
          .addTo(map);
      });
    } else {
      document.getElementById('map').innerHTML = 
        '<div class="h-full flex items-center justify-center p-4 text-gray-500 dark:text-gray-400">Ubicación no disponible</div>';
    }
  @endif

  @if($usuario->rol != 'externo')
    const ubicaciones = @json($ubicaciones);
    
    if (ubicaciones.length > 0 && ubicaciones[0].coords.length > 0) {
      const map = L.map('adminMap').setView(
        [ubicaciones[0].coords[0].lat, ubicaciones[0].coords[0].lng], 
        12
      );
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);
      
      const customIcon = L.icon({
        iconUrl: "{{ Vite::asset('resources/images/markers/evento-icon.png') }}",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
      });
      
      ubicaciones.forEach(evento => {
        if (evento.coords && evento.coords.length > 0) {
          evento.coords.forEach((coord, index) => {
            if (coord.lat && coord.lng) {
              L.marker([coord.lat, coord.lng], { icon: customIcon })
                .bindPopup(`
                  <div class="font-bold">${evento.nombre}</div>
                  <div class="text-xs mt-1">ID: ${evento.id}</div>
                  <div class="text-xs mt-1">${evento.estado}</div>
                `)
                .addTo(map);
            }
          });
        }
      });
    } else {
      document.getElementById('adminMap').innerHTML = 
        '<div class="h-full flex items-center justify-center p-4 text-gray-500 dark:text-gray-400">No hay ubicaciones disponibles</div>';
    }
  @endif
});
</script>
@endsection