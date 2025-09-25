<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            ['nombre' => 'Pachuca', 'descripcion' => 'Pachuca'],
            ['nombre' => 'Querétaro', 'descripcion' => 'Querétaro'],
            ['nombre' => 'Tulancingo', 'descripcion' => 'Tulancingo'],
            ['nombre' => 'Zumpango', 'descripcion' => 'Zumpango'],
            ['nombre' => 'Huachinango', 'descripcion' => 'Huachinango'],
        ];

        foreach ($unidades as $unidad) {
            \DB::table('unidades')->updateOrInsert(
                ['nombre' => $unidad['nombre']],
                [
                    'nombre' => $unidad['nombre'],
                    'descripcion' => $unidad['descripcion'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
