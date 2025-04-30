<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\Pedido;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        // Obtener el usuario autenticado desde los guards 'externo' o 'empleado'
        $usuario = Auth::guard('externo')->user() ?? Auth::guard('empleado')->user();

        if (!$usuario) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión']);
        }

        // Obtener eventos según rol
        if ($usuario->rol == 'externo') {
            $eventos = Evento::where('estado', 'activo')->get();
            $ubicaciones = $this->getUbicacionesExterno($usuario);
        } else {
            $eventos = Evento::all();
            $ubicaciones = $this->getUbicacionesAdmin();
        }

        // Obtener pedidos para usuarios externos
        $pedidos = ($usuario->rol == 'externo' && $usuario->evento_id)
            ? Pedido::with('evento')
                ->where('evento_id', $usuario->evento_id)
                ->where('externo_id', $usuario->id)
                ->orderBy('id', 'desc')
                ->get()
            : collect();

        return view('home', [
            'usuario' => $usuario,
            'eventos' => $eventos,
            'pedidos' => $pedidos,
            'ubicaciones' => $ubicaciones,
            'centerMap' => $this->getCenterMap($ubicaciones)
        ]);
    }

    /**
     * Obtiene ubicaciones para usuarios externos
     */
    protected function getUbicacionesExterno($usuario)
    {
        if (!$usuario->evento_id) return [];

        $evento = Evento::find($usuario->evento_id);
        if (!$evento || empty($evento->ubicacion)) return [];

        return [
            [
                'id' => $evento->id,
                'nombre' => $evento->nombre,
                'coords' => $this->parseUbicacion($evento->ubicacion),
                'tipo' => 'tipo1'
            ]
        ];
    }

    /**
     * Obtiene ubicaciones para administradores
     */
    protected function getUbicacionesAdmin()
    {
        return Evento::select('id', 'nombre', 'ubicacion')
            ->whereNotNull('ubicacion')
            ->get()
            ->map(function($evento) {
                return [
                    'id' => $evento->id,
                    'nombre' => $evento->nombre,
                    'coords' => $this->parseUbicacion($evento->ubicacion),
                    'tipo' => 'tipo1'
                ];
            })
            ->filter(fn($e) => !empty($e['coords']))
            ->values()
            ->toArray();
    }

    /**
     * Parsea la cadena de ubicación a coordenadas
     */
    protected function parseUbicacion($ubicacion)
    {
        if (empty($ubicacion)) return [];

        // Formato esperado: "-16.488892,-68.142961;-16.488962,-68.143030"
        $puntos = explode(';', trim($ubicacion));
        $coordenadas = [];
        
        foreach ($puntos as $punto) {
            $coords = explode(',', trim($punto));
            if (count($coords) === 2) {
                $lat = (float)trim($coords[0]);
                $lng = (float)trim($coords[1]);
                if (!is_nan($lat) && !is_nan($lng)) {
                    $coordenadas[] = [
                        'lat' => $lat,
                        'lng' => $lng
                    ];
                }
            }
        }

        return $coordenadas;
    }

    /**
     * Calcula el centro del mapa basado en las ubicaciones
     */
    protected function getCenterMap($ubicaciones)
    {
        if (empty($ubicaciones)) {
            return ['lat' => -16.4890, 'lng' => -68.1429]; // Coordenadas por defecto (La Paz)
        }

        $firstLocation = $ubicaciones[0]['coords'][0];
        return ['lat' => $firstLocation['lat'], 'lng' => $firstLocation['lng']];
    }
}