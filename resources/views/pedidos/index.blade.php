@extends('layouts.app')

@section('title', 'Lista de Pedidos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
            <i class="fas fa-clipboard-list mr-2 text-primary"></i>
            Lista de Pedidos
        </h1>
        
        @if(Auth::guard('externo')->check() && isset($pedidos) && $pedidos->isNotEmpty())
            @php
                $miEvento = $pedidos->first()->evento ?? null;
            @endphp

            @if($miEvento)
                <a href="{{ route('pedidos.create', $miEvento->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
                    <i class="fas fa-cart-plus"></i>
                    <span>Nuevo Pedido</span>
                </a>
            @endif
        @endif
    </div>

    <!-- Filtro (misma funcionalidad) -->
    <form method="GET" action="{{ route('pedidos.index') }}" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
            <div class="flex-1">
                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <i class="fas fa-filter mr-1"></i>
                    Filtrar por Estado:
                </label>
                <select name="estado" id="estado" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_preparacion">En Preparación</option>
                    <option value="enviado">Enviado</option>
                    <option value="entregado">Entregado</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                <i class="fas fa-search mr-1"></i>
                Filtrar
            </button>
        </div>
    </form>

    @if($pedidos->isEmpty())
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-800 p-4 rounded-lg">
            <i class="fas fa-info-circle mr-2"></i>
            No hay pedidos registrados.
        </div>
    @else
        <!-- Tabla (misma estructura, solo estilos visuales) -->
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Evento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        
                        @if(Auth::guard('empleado')->check() && in_array(Auth::user()->rol, ['admin', 'superadmin']))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($pedidos as $pedido)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $pedido->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $pedido->evento->nombre ?? 'Sin evento' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                            {{ $pedido->externo->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $pedido->cantidad }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">${{ number_format($pedido->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($pedido->estado == 'pendiente')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Pendiente</span>
                            @elseif ($pedido->estado == 'en_preparacion')
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">En Preparación</span>
                            @elseif ($pedido->estado == 'enviado')
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">Enviado</span>
                            @elseif ($pedido->estado == 'entregado')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Entregado</span>
                            @endif
                        </td>
                        
                        @if(Auth::guard('empleado')->check() && in_array(Auth::user()->rol, ['admin', 'superadmin']))
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('pedidos.show', $pedido->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    
                                    <form action="{{ route('pedidos.changeStatus', $pedido->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <select name="estado" class="text-xs p-1 border border-gray-300 dark:border-gray-600 rounded">
                                            <option value="en_preparacion" {{ $pedido->estado == 'en_preparacion' ? 'selected' : '' }}>En Preparación</option>
                                            <option value="enviado" {{ $pedido->estado == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                            <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                        </select>
                                        <button type="submit" class="text-xs bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection