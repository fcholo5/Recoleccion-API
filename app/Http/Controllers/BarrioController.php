<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarrioController extends Controller
{
    /**
     * Muestra todos los barrios.
     */
    public function index()
    {
        $barrios = DB::table('barrios')
            ->select('nombre', 'lat', 'lng')
            ->get();

        return response()->json([
            'barrios' => $barrios
        ]);
    }

    /**
     * Detecta barrios que intersectan con una ruta dada.
     */
    public function barriosPorRuta(Request $request)
    {
        $request->validate([
            'shape' => 'required|array',
            'shape.type' => 'required|string',
            'shape.coordinates' => 'required|array'
        ]);

        $coordinates = [];
        if ($request->shape['type'] === 'LineString') {
            $coordinates = $request->shape['coordinates'];
        } elseif ($request->shape['type'] === 'MultiLineString') {
            foreach ($request->shape['coordinates'] as $line) {
                $coordinates = array_merge($coordinates, $line);
            }
        }

        if (empty($coordinates)) {
            return response()->json(['barrios' => []]);
        }

        $barriosDetectados = [];
        $barrios = DB::table('barrios')->get();

        foreach ($barrios as $barrio) {
            foreach ($coordinates as $coord) {
                [$lng, $lat] = $coord;
                $distancia = $this->distanciaEnKm($lat, $lng, $barrio->lat, $barrio->lng);
                if ($distancia <= 1.0) { // 1 km de radio
                    $barriosDetectados[] = $barrio->nombre;
                    break; // No duplicar
                }
            }
        }

        return response()->json([
            'barrios' => array_values(array_unique($barriosDetectados))
        ]);
    }

    /**
     * Calcula la distancia en kilómetros entre dos puntos.
     */
    private function distanciaEnKm($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Radio de la Tierra en km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }
}