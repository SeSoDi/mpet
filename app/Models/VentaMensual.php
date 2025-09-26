<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaMensual extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'ventas_mensuales';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'periodo_inicio',
        'Prospectos',
        'VentasAproximadasMXN',
        'VentasCerradasMXN',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'periodo_inicio' => 'date',
        'VentasAproximadasMXN' => 'decimal:2',
        'VentasCerradasMXN' => 'decimal:2',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     * Las columnas generadas se incluirán automáticamente
     */
    protected $appends = [];

    /**
     * Scope para filtrar por año
     */
    public function scopePorAnio($query, $anio)
    {
        return $query->where('cal_anio', $anio);
    }

    /**
     * Scope para filtrar por mes
     */
    public function scopePorMes($query, $mes)
    {
        return $query->where('cal_mes', $mes);
    }

    /**
     * Scope para filtrar por año y mes
     */
    public function scopePorPeriodo($query, $anio, $mes)
    {
        return $query->where('cal_yyyymm', ($anio * 100) + $mes);
    }
}
