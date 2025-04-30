<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\Material;
use App\Models\Externo;

class EventoController extends Controller
{
    /**
     * Mostrar formulario para crear evento
     */
    public function create()
    {
        $usuario = Auth::user();
    
        if (!in_array($usuario->rol, ['superadmin', 'master'])) {
            abort(403, 'Acceso no autorizado. Se requiere superadmin o master.');
        }
    
        return view('eventos.create');
    }

    /**
     * Almacenar evento
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();
    
        // Validar
        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'fecha_inicio'       => 'required|date',
            'hora_inicio'        => 'required',
            'fecha_finalizacion' => 'required|date|after_or_equal:fecha_inicio',
            'hora_finalizacion'  => 'required',
            'estado'             => 'required|in:activo,en espera,finalizado',
            'descripcion'        => 'nullable|string',
            'ubicacion'          => 'required|string',
        ]);
    
        // Asignar automáticamente el legajo del usuario autenticado
        $validated['legajo'] = $usuario->legajo;
    
        // Crear el evento
        Evento::create($validated);
    
        return redirect()->route('eventos.admin')
            ->with('success', 'Evento creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'master') {
            abort(403, 'Acceso no autorizado (solo master).');
        }
    
        $evento = Evento::findOrFail($id);
        return view('eventos.edit', compact('evento'));
    }
    
    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'master') {
            abort(403, 'Acceso no autorizado (solo master).');
        }
    
        $evento = Evento::findOrFail($id);
    
        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'fecha_inicio'       => 'required|date',
            'hora_inicio'        => 'required',
            'fecha_finalizacion' => 'required|date|after_or_equal:fecha_inicio',
            'hora_finalizacion'  => 'required',
            'descripcion'        => 'nullable|string',
            'estado'             => 'required|in:activo,en espera,finalizado',
            'ubicacion'          => 'required|string',
        ]);
    
        // Actualizar el evento
        $evento->update($validated);
    
        // Si el estado del evento pasó a 'finalizado',
        // actualizamos la tabla "externos" para desinscribir a los usuarios
        if ($validated['estado'] === 'finalizado') {
            Externo::where('evento_id', $evento->id)
                ->update(['evento_id' => null]);
        }
    
        return redirect()->route('eventos.admin')
            ->with('success', 'Evento actualizado correctamente.');
    }
    
    /**
     * Eliminar evento
     */
    public function destroy($id)
    {
        $usuario = Auth::user();
        if ($usuario->rol !== 'master') {
            abort(403, 'Acceso no autorizado (solo admin o superadmin).');
        }

        $evento = Evento::findOrFail($id);
        $evento->delete();

        return redirect()->route('eventos.admin')
            ->with('success', 'Evento eliminado correctamente.');
    }

    /**
     * Mostrar administración de eventos
     */
    public function admin()
    {
        $usuario = Auth::user();
        
        if (in_array($usuario->rol, ['superadmin', 'master', 'admin'])) {
            $eventos = Evento::all();
        } else {
            $eventos = Evento::where('legajo', $usuario->legajo)->get();
        }
        
        return view('eventos.admin', [
            'usuario' => $usuario,
            'eventos' => $eventos
        ]);
    }
}