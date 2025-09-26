<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas_mensuales', function (Blueprint $table) {
            $table->id();
            $table->date('periodo_inicio')->comment('Primer día del mes');
            $table->integer('Prospectos');
            $table->decimal('VentasAproximadasMXN', 18, 2);
            $table->decimal('VentasCerradasMXN', 18, 2);
            $table->timestamps();
            
            // Índice único sobre periodo_inicio
            $table->unique('periodo_inicio');
        });

        // Agregar columnas generadas usando SQL directo
        DB::statement('ALTER TABLE ventas_mensuales ADD COLUMN cal_anio INT GENERATED ALWAYS AS (YEAR(periodo_inicio)) STORED');
        DB::statement('ALTER TABLE ventas_mensuales ADD COLUMN cal_mes TINYINT GENERATED ALWAYS AS (MONTH(periodo_inicio)) STORED');
        DB::statement('ALTER TABLE ventas_mensuales ADD COLUMN cal_yyyymm INT GENERATED ALWAYS AS (YEAR(periodo_inicio)*100 + MONTH(periodo_inicio)) STORED');
        
        // Crear índices sobre las columnas generadas
        DB::statement('CREATE INDEX idx_ventas_mensuales_cal_yyyymm ON ventas_mensuales (cal_yyyymm)');
        DB::statement('CREATE INDEX idx_ventas_mensuales_cal_anio ON ventas_mensuales (cal_anio)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_mensuales');
    }
};
