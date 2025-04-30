@extends('layouts.app')

@section('title', 'Cancelar Inscripción')

@section('content')
<div class="max-w-md mx-auto p-6 bg-lightCard/90 dark:bg-darkCard/90 rounded-2xl shadow-xl border border-primary/20 dark:border-accentBlue/30 transition-all duration-300 hover:shadow-[0_0_20px_rgba(255,42,109,0.2)]">
    <!-- Título con efecto de gradiente -->
    <h2 class="text-2xl font-bold mb-4 tracking-wide">
        <span class="bg-gradient-to-r from-danger to-danger/80 text-transparent bg-clip-text">
            <i class="fas fa-times-circle mr-2"></i>
            Cancelar Inscripción
        </span>
    </h2>
    
    <!-- Descripción -->
    <p class="mb-6 text-gray-700 dark:text-gray-300">
        Estás a punto de cancelar tu inscripción al evento: 
        <strong class="text-primary dark:text-accentBlue">{{ $evento->nombre }}</strong>
    </p>

    <!-- Mensaje de error -->
    @if(session('error'))
    <div class="mb-6 p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center gap-2">
        <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Formulario -->
    <form method="POST" action="{{ route('inscripciones.cancel', $evento->id) }}" class="space-y-4">
        @csrf
        
        <!-- Selector de motivo -->
        <div>
            <label for="motivo" class="block mb-2 text-sm font-semibold text-primary dark:text-accentBlue">
                <i class="fas fa-comment-dots mr-1"></i>
                Motivo de cancelación
            </label>
            <select name="motivo" id="motivo" required
                class="w-full p-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm focus:ring-2 focus:ring-danger/50 focus:border-danger transition-all duration-200">
                <option value="">Selecciona un motivo...</option>
                <option value="Cambio de planes">Cambio de planes</option>
                <option value="Problemas personales">Problemas personales</option>
                <option value="No puedo asistir">No puedo asistir</option>
                <option value="Otro">Otro</option>
            </select>
        </div>

        <!-- Campo de texto adicional -->
        <div>
            <label for="motivo_extra" class="block mb-2 text-sm font-semibold text-primary dark:text-accentBlue">
                <i class="fas fa-edit mr-1"></i>
                Detalles adicionales (opcional)
            </label>
            <textarea name="motivo_extra" id="motivo_extra" rows="3" 
                class="w-full p-3 bg-white/90 dark:bg-gray-800/90 border border-primary/30 dark:border-accentBlue/50 rounded-xl shadow-sm focus:ring-2 focus:ring-danger/50 focus:border-danger transition-all duration-200"
                placeholder="Por favor, comparte más detalles sobre tu cancelación..."></textarea>
        </div>

        <!-- Botón de confirmación -->
        <button type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-danger to-danger/80 text-white font-semibold rounded-xl shadow-lg hover:shadow-[0_0_15px_rgba(255,42,109,0.4)] transition-all duration-300 hover:scale-[1.02] flex items-center justify-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Confirmar Cancelación</span>
        </button>

        <!-- Botón de regreso -->
        <a href="{{ url()->previous() }}" 
           class="block mt-3 text-center text-sm font-medium text-primary dark:text-accentBlue hover:text-secondary dark:hover:text-accentPink transition-colors duration-200">
            <i class="fas fa-arrow-left mr-1"></i>
            Volver atrás
        </a>
    </form>
</div>

<!-- Script para mostrar/ocultar textarea cuando se selecciona "Otro" -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const motivoSelect = document.getElementById('motivo');
    const motivoExtra = document.getElementById('motivo_extra');
    
    function toggleMotivoExtra() {
        if (motivoSelect.value === 'Otro') {
            motivoExtra.setAttribute('required', '');
        } else {
            motivoExtra.removeAttribute('required');
        }
    }
    
    motivoSelect.addEventListener('change', toggleMotivoExtra);
    toggleMotivoExtra(); // Ejecutar al cargar
});
</script>
@endsection