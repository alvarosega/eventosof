@extends('layouts.app')

@section('title', 'Administrar Eventos en Tarjetas')

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

    {{-- Sección de Gestión de Eventos --}}
    <div class="bg-lightCard/90 dark:bg-darkCard/90 rounded-xl shadow-2xl mb-8 border border-primary/20 dark:border-accentBlue/30 overflow-hidden transition-all duration-300 hover:shadow-[0_0_20px_rgba(0,198,255,0.2)]">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-primary to-secondary p-4 border-b border-accentBlue/20 flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-tools text-accentPink"></i>
                <h3 class="font-semibold tracking-wide">Gestión de Eventos</h3>
            </div>
            
            @if(in_array($usuario->rol, ['superadmin', 'master']))
                <a href="{{ route('eventos.create') }}" 
                   class="bg-gradient-to-r from-accentPink to-accentPink/80 hover:from-accentPink hover:to-accentPink/90 text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_15px_rgba(255,45,247,0.3)] transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo Evento</span>
                </a>
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
                                <p class="mb-2"><span class="font-medium">Legajo:</span> {{ $evento->legajo }}</p>
                                <p class="mb-2"><span class="font-medium">Ubicación:</span> {{ $evento->ubicacion }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-3">
                                    {{ Str::limit($evento->descripcion, 100) }}
                                </p>
                            </div>
                            
                            <!-- Pie de la tarjeta -->
                            <div class="p-4 border-t border-primary/20 dark:border-accentBlue/30">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs text-primary/70 dark:text-accentBlue/70">Acciones</span>
                                    
                                    @if($usuario->rol === 'master')
                                        <div class="flex flex-col space-y-1">
                                            {{-- Botón de Editar (MANTENIDO IGUAL QUE EL ORIGINAL) --}}
                                            <a href="{{ route('eventos.edit', $evento->id) }}" 
                                               class="text-blue-600 underline hover:text-blue-800 text-sm">
                                                ✏️ Editar
                                            </a>

                                            {{-- Botón de Eliminar (MANTENIDO IGUAL QUE EL ORIGINAL) --}}
                                            <a href="#" onclick="if(confirm('¿Eliminar este evento?')) { document.getElementById('delete-form-{{ $evento->id }}').submit(); }"
                                               class="text-red-600 underline hover:text-red-800 text-sm">
                                                🗑️ Eliminar
                                            </a>

                                            {{-- Formulario oculto para eliminar (MANTENIDO IGUAL) --}}
                                            <form id="delete-form-{{ $evento->id }}" action="{{ route('eventos.destroy', $evento->id) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($usuario->rol != 'externo')
                                    <a href="{{ route('pedidos.evento', $evento->id) }}" 
                                       class="block bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 py-2 rounded-xl shadow-md hover:shadow-[0_0_10px_rgba(0,0,0,0.2)] transition-all duration-300 text-center">
                                        <i class="fas fa-clipboard-list mr-1"></i>
                                        <span>Ver Pedidos</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection