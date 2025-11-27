<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarriosSeeder extends Seeder
{
    public function run(): void
    {
        $barrios = [
            ['nombre' => 'Centro', 'lat' => 3.8900, 'lng' => -77.0500],
            ['nombre' => 'Boroboro', 'lat' => 3.8800, 'lng' => -77.0400],
            ['nombre' => 'San José', 'lat' => 3.8850, 'lng' => -77.0600],
            ['nombre' => 'Juan XXIII', 'lat' => 3.8750, 'lng' => -77.0450],
            ['nombre' => 'La Playa', 'lat' => 3.8950, 'lng' => -77.0550],
            ['nombre' => 'Viento Libre', 'lat' => 3.8700, 'lng' => -77.0350],
            ['nombre' => 'Morales', 'lat' => 3.9000, 'lng' => -77.0650],
            ['nombre' => 'Aguablanca', 'lat' => 3.8650, 'lng' => -77.0300],
            ['nombre' => 'Ciudadela', 'lat' => 3.8880, 'lng' => -77.0520],
            ['nombre' => 'Obrero', 'lat' => 3.8820, 'lng' => -77.0480],
            ['nombre' => 'Lleras', 'lat' => 3.8780, 'lng' => -77.0520],
            ['nombre' => 'Kennedy', 'lat' => 3.8720, 'lng' => -77.0420],
            ['nombre' => 'Santa Rosa', 'lat' => 3.8920, 'lng' => -77.0620],
            ['nombre' => 'Nueva Granada', 'lat' => 3.8680, 'lng' => -77.0380],
            ['nombre' => 'Bellavista', 'lat' => 3.9020, 'lng' => -77.0700],
        ];

        DB::table('barrios')->insert($barrios);
    }
}