<?php

namespace App\Http\Controllers;

use App\Models\FacturacionMensual;
use App\Models\Unidad;
use App\Services\LogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class FacturacionMensualController extends Controller
{
    /**
     * Display the billing interface for a specific month/year/unit.
     */
    public function index(Request $request): Response
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));
        $unidad = $request->get('unidad', 'Pachuca'); // Default a primera unidad
        $edit = $request->get('edit', false);

        // Buscar registro existente para el mes/año/unidad especificado
        $facturacionActual = FacturacionMensual::porAnio($year)
            ->porMes($month)
            ->porUnidad($unidad)
            ->first();

        // Obtener unidades activas para el dropdown
        $unidadesActivas = Unidad::activas()
            ->orderBy('nombre')
            ->get(['nombre', 'descripcion']);

        return Inertia::render('Facturacion/Index', [
            'facturacionActual' => $facturacionActual,
            'anioActual' => (int) $year,
            'mesActual' => (int) $month,
            'unidadActual' => $unidad,
            'unidadesActivas' => $unidadesActivas,
            'forceEdit' => $edit === 'true',
        ]);
    }

    /**
     * Display a paginated list of all records (for Datos Brutos section).
     */
    public function list(Request $request): Response
    {
        // Filtros de consulta
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        $unidad = $request->get('unidad');

        $query = FacturacionMensual::query()
            ->with('unidadModel')  // Incluir la relación con Unidad
            ->orderBy('periodo_inicio', 'desc')
            ->orderBy('unidad');

        // Filtrar por año si se especifica
        if ($year) {
            $query->porAnio($year);
        }

        // Filtrar por mes si se especifica
        if ($month) {
            $query->porMes($month);
        }

        // Filtrar por unidad si se especifica
        if ($unidad) {
            $query->porUnidad($unidad);
        }

        // Para estadísticas, obtener todos los registros aplicando los mismos filtros (sin paginación)
        $queryEstadisticas = FacturacionMensual::query()
            ->with('unidadModel')
            ->orderBy('periodo_inicio', 'desc')
            ->orderBy('unidad');

        // Aplicar los mismos filtros que la consulta principal
        if ($year) {
            $queryEstadisticas->porAnio($year);
        }
        if ($month) {
            $queryEstadisticas->porMes($month);
        }
        if ($unidad) {
            $queryEstadisticas->porUnidad($unidad);
        }

        $todosLosRegistros = $queryEstadisticas->get();

        // Para la tabla, usar paginación
        $facturacion = $query->paginate(15)->withQueryString();

        // Obtener años disponibles para el filtro
        $yearsAvailable = FacturacionMensual::selectRaw('DISTINCT cal_anio as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Obtener unidades disponibles para el filtro
        $unidadesAvailable = FacturacionMensual::selectRaw('DISTINCT unidad')
            ->orderBy('unidad')
            ->pluck('unidad')
            ->toArray();

        return Inertia::render('Facturacion/List', [
            'facturacion' => $facturacion,
            'todosLosRegistros' => $todosLosRegistros,
            'filters' => [
                'year' => $year,
                'month' => $month,
                'unidad' => $unidad,
            ],
            'yearsAvailable' => $yearsAvailable,
            'unidadesAvailable' => $unidadesAvailable,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'unidad' => 'required|string|max:100',
            'facturado_total_mxn' => 'required|numeric|min:0',
            'consumo_total_m2' => 'required|numeric|min:0',
            'facturas_emitidas' => 'required|integer|min:0',
            'facturas_canceladas' => 'required|integer|min:0',
            'facturas_contado' => 'required|integer|min:0',
            'facturacion_efectivo' => 'nullable|numeric|min:0',
        ], [
            'year.required' => 'El año es obligatorio',
            'month.required' => 'El mes es obligatorio',
            'unidad.required' => 'La unidad es obligatoria',
            'facturado_total_mxn.required' => 'El facturado total es obligatorio',
            'consumo_total_m2.required' => 'El consumo total es obligatorio',
            'facturas_emitidas.required' => 'El número de facturas emitidas es obligatorio',
        ]);

        // Crear la fecha del primer día del mes
        $periodoInicio = Carbon::create($validated['year'], $validated['month'], 1)->format('Y-m-d');

        try {
            $facturacion = FacturacionMensual::create([
                'periodo_inicio' => $periodoInicio,
                'unidad' => $validated['unidad'],
                'facturado_total_mxn' => $validated['facturado_total_mxn'],
                'consumo_total_m2' => $validated['consumo_total_m2'],
                'facturas_emitidas' => $validated['facturas_emitidas'],
                'facturas_canceladas' => $validated['facturas_canceladas'],
                'facturas_contado' => $validated['facturas_contado'],
                'facturacion_efectivo' => $validated['facturacion_efectivo'],
            ]);

            // Log the creation
            LogService::contabilidad('created', [
                'facturacion_id' => $facturacion->id,
                'periodo' => $facturacion->periodo_inicio->format('Y-m'),
                'unidad' => $facturacion->unidad,
                'facturado_total' => $facturacion->facturado_total_mxn,
            ]);

            return redirect()->route('monthly-billing.index', [
                'year' => $validated['year'],
                'month' => $validated['month'],
                'unidad' => $validated['unidad']
            ])->with('success', 'Facturación mensual registrada correctamente');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Duplicate entry
                return back()->withErrors([
                    'periodo' => 'Ya existe un registro para este periodo (' . 
                               Carbon::create($validated['year'], $validated['month'])->format('M Y') . 
                               ') y unidad (' . $validated['unidad'] . ')'
                ])->withInput();
            }
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FacturacionMensual $monthly_billing): Response
    {
        return Inertia::render('Facturacion/Show', [
            'facturacion' => $monthly_billing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FacturacionMensual $monthly_billing): Response
    {
        return Inertia::render('Facturacion/Edit', [
            'facturacion' => $monthly_billing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FacturacionMensual $monthly_billing)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'unidad' => 'required|string|max:100',
            'facturado_total_mxn' => 'required|numeric|min:0',
            'consumo_total_m2' => 'required|numeric|min:0',
            'facturas_emitidas' => 'required|integer|min:0',
            'facturas_canceladas' => 'required|integer|min:0',
            'facturas_contado' => 'required|integer|min:0',
            'facturacion_efectivo' => 'nullable|numeric|min:0',
        ]);

        // Store old values before update for logging
        $oldValues = $monthly_billing->getOriginal();
        
        // Crear la fecha del primer día del mes
        $periodoInicio = Carbon::create($validated['year'], $validated['month'], 1)->format('Y-m-d');

        try {
            $monthly_billing->update([
                'periodo_inicio' => $periodoInicio,
                'unidad' => $validated['unidad'],
                'facturado_total_mxn' => $validated['facturado_total_mxn'],
                'consumo_total_m2' => $validated['consumo_total_m2'],
                'facturas_emitidas' => $validated['facturas_emitidas'],
                'facturas_canceladas' => $validated['facturas_canceladas'],
                'facturas_contado' => $validated['facturas_contado'],
                'facturacion_efectivo' => $validated['facturacion_efectivo'],
            ]);

            // Log the update action
            LogService::contabilidad('updated', [
                'facturacion_id' => $monthly_billing->id,
                'periodo' => $monthly_billing->periodo_inicio->format('Y-m'),
                'unidad' => $monthly_billing->unidad,
                'changes' => array_keys($monthly_billing->getChanges()),
            ]);

            return redirect()->route('monthly-billing.index', [
                'year' => $validated['year'],
                'month' => $validated['month'],
                'unidad' => $validated['unidad']
            ])->with('success', 'Facturación mensual actualizada correctamente');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Duplicate entry
                return back()->withErrors([
                    'periodo' => 'Ya existe un registro para este periodo y unidad'
                ])->withInput();
            }
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacturacionMensual $monthly_billing)
    {
        // Capturar los datos antes de eliminar para el log
        $deletedData = [
            'id' => $monthly_billing->id,
            'periodo_inicio' => $monthly_billing->periodo_inicio,
            'unidad' => $monthly_billing->unidad,
            'facturado_total_mxn' => $monthly_billing->facturado_total_mxn,
            'consumo_total_m2' => $monthly_billing->consumo_total_m2,
            'facturas_emitidas' => $monthly_billing->facturas_emitidas,
            'facturas_canceladas' => $monthly_billing->facturas_canceladas,
            'facturas_contado' => $monthly_billing->facturas_contado,
            'facturacion_efectivo' => $monthly_billing->facturacion_efectivo,
        ];

        $monthly_billing->delete();

        // Log the deletion
        LogService::contabilidad('deleted', [
            'facturacion_id' => $monthly_billing->id,
            'periodo' => $deletedData['periodo_inicio'],
            'unidad' => $deletedData['unidad'],
            'facturado_total' => $deletedData['facturado_total_mxn'],
        ]);

        return redirect()->route('monthly-billing.index')
            ->with('success', 'Facturación mensual eliminada correctamente');
    }
}
