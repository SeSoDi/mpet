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
        Schema::create('facturacion_mensual', function (Blueprint $table) {
            $table->id();
            $table->date('periodo_inicio');
            $table->string('unidad', 100);
            $table->decimal('facturado_total_mxn', 15, 2)->default(0.00);
            $table->decimal('consumo_total_m2', 12, 6)->default(0.000000);
            $table->unsignedInteger('facturas_emitidas')->default(0);
            $table->unsignedInteger('facturas_canceladas')->default(0);
            $table->unsignedInteger('facturas_contado')->default(0);
            $table->decimal('facturacion_efectivo', 15, 2)->nullable();
            
            // Campos calculados para filtros (database-agnostic)
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'sqlite') {
                $table->integer('cal_anio')->storedAs('CAST(strftime(\'%Y\', periodo_inicio) AS INTEGER)');
                $table->integer('cal_mes')->storedAs('CAST(strftime(\'%m\', periodo_inicio) AS INTEGER)');
                $table->integer('cal_yyyymm')->storedAs('CAST(strftime(\'%Y\', periodo_inicio) AS INTEGER) * 100 + CAST(strftime(\'%m\', periodo_inicio) AS INTEGER)');
            } else {
                // MySQL/MariaDB
                $table->integer('cal_anio')->storedAs('YEAR(periodo_inicio)');
                $table->integer('cal_mes')->storedAs('MONTH(periodo_inicio)');
                $table->integer('cal_yyyymm')->storedAs('YEAR(periodo_inicio) * 100 + MONTH(periodo_inicio)');
            }
            
            $table->timestamps();
            
            // Índices
            $table->index(['periodo_inicio', 'unidad'], 'idx_periodo_unidad');
            $table->index(['cal_anio', 'cal_mes'], 'idx_cal_anio_mes');
            $table->unique(['cal_yyyymm', 'unidad'], 'unique_yyyymm_unidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion_mensual');
    }
};
