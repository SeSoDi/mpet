<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacturacionMensualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Solo ejecutar en entorno de pruebas o desarrollo
        if (!app()->environment(['local', 'testing'])) {
            $this->command->warn('Test data seeding skipped in production environment');
            return;
        }

        $unidades = ['Pachuca', 'Querétaro', 'Tulancingo', 'Zumpango', 'Huachinango'];
        $testData = [];

        // Generar datos para los últimos 6 meses para cada unidad
        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i)->startOfMonth();
            
            foreach ($unidades as $unidad) {
                $baseFacturado = rand(500000, 2000000); // Entre 500k y 2M
                $facturas_emitidas = rand(50, 200);
                $facturas_canceladas = rand(2, 15);
                $facturas_contado = rand(10, $facturas_emitidas - $facturas_canceladas);
                
                $testData[] = [
                    'periodo_inicio' => $fecha->format('Y-m-d'),
                    'unidad' => $unidad,
                    'facturado_total_mxn' => $baseFacturado + rand(-50000, 100000),
                    'consumo_total_m2' => rand(1000, 5000) + (rand(0, 999999) / 1000000), // Con decimales
                    'facturas_emitidas' => $facturas_emitidas,
                    'facturas_canceladas' => $facturas_canceladas,
                    'facturas_contado' => $facturas_contado,
                    'facturacion_efectivo' => rand(0, 1) ? rand(50000, 300000) : null, // 50% opcional
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar datos de prueba
        foreach (array_chunk($testData, 50) as $chunk) {
            \DB::table('facturacion_mensual')->insert($chunk);
        }

        $this->command->info('Created ' . count($testData) . ' test records for facturacion_mensual');
    }
}
