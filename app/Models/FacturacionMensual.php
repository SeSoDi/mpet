<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class FacturacionMensual extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'facturacion_mensual';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'periodo_inicio',
        'unidad',
        'facturado_total_mxn',
        'consumo_total_m2',
        'facturas_emitidas',
        'facturas_canceladas',
        'facturas_contado',
        'facturacion_efectivo',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'periodo_inicio' => 'date',
        'facturado_total_mxn' => 'decimal:2',
        'consumo_total_m2' => 'decimal:6',
        'facturacion_efectivo' => 'decimal:2',
        'facturas_emitidas' => 'integer',
        'facturas_canceladas' => 'integer',
        'facturas_contado' => 'integer',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     * Las columnas generadas se incluirán automáticamente
     */
    protected $appends = [];

    /**
     * Scope para filtrar por año
     */
    public function scopePorAnio(Builder $query, int $anio): Builder
    {
        return $query->where('cal_anio', $anio);
    }

    /**
     * Scope para filtrar por mes
     */
    public function scopePorMes(Builder $query, int $mes): Builder
    {
        return $query->where('cal_mes', $mes);
    }

    /**
     * Scope para filtrar por año y mes
     */
    public function scopePorPeriodo(Builder $query, int $anio, int $mes): Builder
    {
        return $query->where('cal_yyyymm', ($anio * 100) + $mes);
    }

    /**
     * Scope para filtrar por unidad
     */
    public function scopePorUnidad(Builder $query, string $unidad): Builder
    {
        return $query->where('unidad', $unidad);
    }

    /**
     * Relación con unidad (soft relationship por nombre)
     */
    public function unidadModel()
    {
        return $this->belongsTo(Unidad::class, 'unidad', 'nombre');
    }
}
