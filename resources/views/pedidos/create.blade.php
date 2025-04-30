@extends('layouts.app')

@section('title', 'Crear Pedido')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-lightCard/90 dark:bg-darkCard/90 rounded-2xl shadow-2xl border border-primary/20 dark:border-accentBlue/30">
    <!-- Título con gradiente -->
    <h2 class="text-2xl font-bold mb-6 tracking-wide">
        <span class="bg-gradient-to-r from-accentBlue to-accentPink text-transparent bg-clip-text">
            <i class="fas fa-cart-plus mr-2"></i>
            Crear Pedido
        </span>
        <span class="text-primary dark:text-accentBlue">para Evento:</span>
        <span class="text-secondary dark:text-accentPink">{{ $eventoId }}</span>
    </h2>

    <!-- Mostrar errores con estilo mejorado -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs">
            <ul class="space-y-2">
                @foreach($errors->all() as $error)
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $error }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pedidos.store', $eventoId) }}" method="POST">
        @csrf
        <div class="space-y-4">
            @forelse($productos as $producto)
                <!-- Tarjeta de producto -->
                <div class="flex items-center gap-4 p-4 bg-lightCard dark:bg-darkCard rounded-xl border border-primary/20 dark:border-accentBlue/30 shadow-sm hover:shadow-md transition-all duration-300">
                    <!-- Imagen del producto -->
                    <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-lg border border-primary/20 dark:border-accentBlue/30">
                        <img src="{{ Storage::url($producto->imagen) }}" alt="{{ $producto->nombre }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    
                    <!-- Información del producto -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-primary dark:text-accentBlue">{{ $producto->nombre }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $producto->descripcion }}</p>
                        <div class="flex flex-wrap gap-4 mt-2">
                            <p class="text-sm">
                                <span class="font-medium">Precio:</span> 
                                <span class="text-success dark:text-success/90">${{ number_format($producto->precio, 2) }}</span>
                            </p>
                            <p class="text-sm">
                                <span class="font-medium">Stock:</span> 
                                <span class="{{ $producto->stock_disponible > 0 ? 'text-info dark:text-info/90' : 'text-danger dark:text-danger/90' }}">
                                    {{ $producto->stock_disponible }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Cantidad y subtotal -->
                    <div class="flex items-center gap-4">
                        <!-- Campo de cantidad -->
                        <div>
                            <label for="producto_{{ $producto->id }}" class="block text-sm font-medium mb-1 text-primary dark:text-accentBlue">
                                Cantidad
                            </label>
                            <input 
                                type="number" 
                                name="productos[{{ $producto->id }}]" 
                                id="producto_{{ $producto->id }}"
                                class="w-20 p-2 border border-primary/30 dark:border-accentBlue/50 rounded-lg bg-white/90 dark:bg-gray-800/90 text-center cantidad-input focus:ring-2 focus:ring-accentBlue/50 focus:border-accentBlue transition-all"
                                min="0"
                                max="{{ $producto->stock_disponible }}"
                                value="0"
                                data-precio="{{ $producto->precio }}"
                            />
                        </div>
                        
                        <!-- Subtotal -->
                        <div class="w-28 text-right">
                            <p class="text-sm font-medium text-primary dark:text-accentBlue">Subtotal</p>
                            <p id="subtotal_{{ $producto->id }}" class="subtotal text-lg font-semibold text-success dark:text-success/90">$0.00</p>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Mensaje cuando no hay productos -->
                <div class="p-6 bg-info/10 border-l-4 border-info text-info dark:text-info/80 rounded-lg flex items-center gap-2">
                    <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">No hay productos disponibles para este evento.</span>
                </div>
            @endforelse
        </div>

        <!-- Total y botón de envío -->
        <div class="mt-8 pt-4 border-t border-primary/20 dark:border-accentBlue/30 text-right">
            <div class="inline-flex items-center gap-4 bg-primary/10 dark:bg-accentBlue/10 px-6 py-3 rounded-xl">
                <p class="text-xl font-bold text-primary dark:text-accentBlue">Total Pedido:</p>
                <p class="text-2xl font-bold text-success dark:text-success/90">$<span id="totalPedido">0.00</span></p>
            </div>
            
            <button type="submit" 
                    class="mt-6 px-6 py-3 bg-gradient-to-r from-primary to-accentBlue text-white font-semibold rounded-xl shadow-lg hover:shadow-[0_0_15px_rgba(0,209,255,0.4)] transition-all duration-300 hover:scale-[1.02] flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Confirmar Pedido</span>
            </button>
        </div>
    </form>
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Función para actualizar totales
    function updateTotals() {
        let totalPedido = 0;
        const inputs = document.querySelectorAll('.cantidad-input');
        
        inputs.forEach(function(input) {
            const cantidad = parseFloat(input.value) || 0;
            const precio = parseFloat(input.getAttribute('data-precio')) || 0;
            const subtotal = cantidad * precio;
            const productoId = input.id.split('_')[1];
            const subtotalEl = document.getElementById('subtotal_' + productoId);
            
            if(subtotalEl) {
                subtotalEl.textContent = '$' + subtotal.toFixed(2);
                
                // Efecto visual al cambiar cantidad
                if (cantidad > 0) {
                    subtotalEl.classList.add('animate-pulse');
                    setTimeout(() => subtotalEl.classList.remove('animate-pulse'), 300);
                }
            }
            
            totalPedido += subtotal;
        });
        
        // Actualizar total con efecto
        const totalEl = document.getElementById('totalPedido');
        totalEl.textContent = totalPedido.toFixed(2);
        
        if (totalPedido > 0) {
            totalEl.classList.add('animate-bounce');
            setTimeout(() => totalEl.classList.remove('animate-bounce'), 1000);
        }
    }
    
    // Event listeners para los inputs de cantidad
    const cantidadInputs = document.querySelectorAll('.cantidad-input');
    cantidadInputs.forEach(function(input) {
        input.addEventListener('input', updateTotals);
        
        // Validación en tiempo real
        input.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max')) || 0;
            const value = parseInt(this.value) || 0;
            
            if (value > max) {
                this.value = max;
                updateTotals();
                
                // Mostrar feedback visual
                this.classList.add('border-danger', 'ring-2', 'ring-danger/50');
                setTimeout(() => {
                    this.classList.remove('border-danger', 'ring-2', 'ring-danger/50');
                }, 2000);
            }
        });
    });
    
    // Validación del formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const total = parseFloat(document.getElementById('totalPedido').textContent) || 0;
        
        if (total <= 0) {
            e.preventDefault();
            
            // Crear mensaje de error
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mb-6 p-4 bg-danger/10 border-l-4 border-danger text-danger dark:text-danger/80 rounded-lg shadow-inner backdrop-blur-xs flex items-center gap-2';
            errorDiv.innerHTML = `
                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">Debes seleccionar al menos un producto para crear el pedido.</span>
            `;
            
            // Insertar antes del formulario
            form.parentNode.insertBefore(errorDiv, form);
            
            // Hacer scroll al mensaje
            errorDiv.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>
