<?php

namespace App\Http\Controllers;

use App\Models\VentaMensual;
use App\Services\LogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class VentaMensualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));
        $edit = $request->get('edit', false);

        // Buscar registro existente para el mes/año especificado
        $ventaActual = VentaMensual::porAnio($year)
            ->porMes($month)
            ->first();

        return Inertia::render('VentasMensuales/Index', [
            'ventaActual' => $ventaActual,
            'anioActual' => (int) $year,
            'mesActual' => (int) $month,
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

        $query = VentaMensual::query()
            ->orderBy('periodo_inicio', 'desc');

        // Filtrar por año si se especifica
        if ($year) {
            $query->porAnio($year);
        }

        // Filtrar por mes si se especifica
        if ($month) {
            $query->porMes($month);
        }

        $ventas = $query->paginate(12)->withQueryString();

        // Obtener años disponibles para el filtro
        $yearsAvailable = VentaMensual::selectRaw('DISTINCT cal_anio as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return Inertia::render('VentasMensuales/List', [
            'ventas' => $ventas,
            'filters' => [
                'year' => $year,
                'month' => $month,
            ],
            'yearsAvailable' => $yearsAvailable,
        ]);
    }

    /**
     * Show the capture interface for a specific month/year.
     */
    public function capture(Request $request): Response
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));
        $edit = $request->get('edit', false);

        // Buscar registro existente para el mes/año especificado
        $ventaActual = VentaMensual::porAnio($year)
            ->porMes($month)
            ->first();

        return Inertia::render('VentasMensuales/Capture', [
            'ventaActual' => $ventaActual,
            'anioActual' => (int) $year,
            'mesActual' => (int) $month,
            'forceEdit' => $edit === 'true',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('VentasMensuales/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'Prospectos' => 'required|integer|min:0',
            'VentasAproximadasMXN' => 'required|numeric|min:0',
            'VentasCerradasMXN' => 'required|numeric|min:0',
        ], [
            'year.required' => 'El año es obligatorio',
            'year.min' => 'El año debe ser mayor a 2020',
            'year.max' => 'El año debe ser menor a 2030',
            'month.required' => 'El mes es obligatorio',
            'month.min' => 'El mes debe ser entre 1 y 12',
            'month.max' => 'El mes debe ser entre 1 y 12',
            'Prospectos.required' => 'Los prospectos son obligatorios',
            'Prospectos.min' => 'Los prospectos deben ser mayor o igual a 0',
            'VentasAproximadasMXN.required' => 'Las ventas aproximadas son obligatorias',
            'VentasAproximadasMXN.min' => 'Las ventas aproximadas deben ser mayor o igual a 0',
            'VentasCerradasMXN.required' => 'Las ventas cerradas son obligatorias',
            'VentasCerradasMXN.min' => 'Las ventas cerradas deben ser mayor o igual a 0',
        ]);

        // Crear la fecha del primer día del mes
        $periodoInicio = Carbon::create($validated['year'], $validated['month'], 1)->format('Y-m-d');

        try {
            $ventaMensual = VentaMensual::create([
                'periodo_inicio' => $periodoInicio,
                'Prospectos' => $validated['Prospectos'],
                'VentasAproximadasMXN' => $validated['VentasAproximadasMXN'],
                'VentasCerradasMXN' => $validated['VentasCerradasMXN'],
            ]);

            // Log the creation
            LogService::ventas('created', [
                'id' => $ventaMensual->id,
                'periodo_inicio' => $periodoInicio,
                'Prospectos' => $validated['Prospectos'],
                'VentasAproximadasMXN' => $validated['VentasAproximadasMXN'],
                'VentasCerradasMXN' => $validated['VentasCerradasMXN'],
            ]);

            return redirect()->route('monthly-sales.index')
                ->with('success', 'Venta mensual registrada correctamente');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Duplicate entry
                return back()->withErrors([
                    'periodo' => 'Ya existe un registro para este periodo (' . 
                               Carbon::create($validated['year'], $validated['month'])->format('M Y') . ')'
                ])->withInput();
            }
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VentaMensual $monthly_sale): Response
    {
        return Inertia::render('VentasMensuales/Show', [
            'venta' => $monthly_sale
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VentaMensual $monthly_sale): Response
    {
        return Inertia::render('VentasMensuales/Edit', [
            'venta' => $monthly_sale
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VentaMensual $monthly_sale)
    {
        // Debug: Check if model is loaded
        \Log::debug('UPDATE METHOD DEBUG:', [
            'ventaMensual_id' => $monthly_sale->id ?? 'NULL',
            'ventaMensual_exists' => $monthly_sale->exists,
            'route_param' => $request->route('monthly_sale'),
            'all_route_params' => $request->route()->parameters(),
        ]);

        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'Prospectos' => 'required|integer|min:0',
            'VentasAproximadasMXN' => 'required|numeric|min:0',
            'VentasCerradasMXN' => 'required|numeric|min:0',
        ], [
            'year.required' => 'El año es obligatorio',
            'month.required' => 'El mes es obligatorio',
            'Prospectos.required' => 'Los prospectos son obligatorios',
            'VentasAproximadasMXN.required' => 'Las ventas aproximadas son obligatorias',
            'VentasCerradasMXN.required' => 'Las ventas cerradas son obligatorias',
        ]);

        // Crear la fecha del primer día del mes
        $periodoInicio = Carbon::create($validated['year'], $validated['month'], 1)->format('Y-m-d');

        try {
        // Store old values before update for logging (if model exists)
        $oldValues = null;
        if ($monthly_sale->exists) {
            $oldValues = $monthly_sale->getOriginal();
            \Log::debug('OLD VALUES CAPTURED:', ['old_values' => $oldValues]);
        } else {
            \Log::error('CANNOT CAPTURE OLD VALUES - MODEL DOESNT EXIST');
        }            $monthly_sale->update([
                'periodo_inicio' => $periodoInicio,
                'Prospectos' => $validated['Prospectos'],
                'VentasAproximadasMXN' => $validated['VentasAproximadasMXN'],
                'VentasCerradasMXN' => $validated['VentasCerradasMXN'],
            ]);

            // Log the update
            LogService::ventas('updated', [
                'id' => $monthly_sale->id,
                'periodo_inicio' => $periodoInicio,
                'old_values' => $oldValues,
                'new_values' => [
                    'Prospectos' => $validated['Prospectos'],
                    'VentasAproximadasMXN' => $validated['VentasAproximadasMXN'],
                    'VentasCerradasMXN' => $validated['VentasCerradasMXN'],
                ],
            ]);

            return redirect()->route('monthly-sales.index')
                ->with('success', 'Venta mensual actualizada correctamente');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Duplicate entry
                return back()->withErrors([
                    'periodo' => 'Ya existe un registro para este periodo'
                ])->withInput();
            }
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VentaMensual $monthly_sale)
    {
        // Capturar los datos antes de eliminar para el log
        $deletedData = [
            'id' => $monthly_sale->id,
            'periodo_inicio' => $monthly_sale->periodo_inicio,
            'Prospectos' => $monthly_sale->Prospectos,
            'VentasAproximadasMXN' => $monthly_sale->VentasAproximadasMXN,
            'VentasCerradasMXN' => $monthly_sale->VentasCerradasMXN,
        ];

        $monthly_sale->delete();

        // Log the deletion
        LogService::ventas('deleted', $deletedData);

        return redirect()->route('monthly-sales.index')
            ->with('success', 'Venta mensual eliminada correctamente');
    }
}
