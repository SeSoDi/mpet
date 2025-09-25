<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class UnidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        $perPage = $request->get('perPage', 15);
        
        $query = Unidad::query()->orderBy('nombre');
        
        if ($search) {
            $query->porNombre($search);
        }
        
        $unidades = $query->paginate($perPage)->withQueryString();
        
        return Inertia::render('Configuracion/Unidades/Index', [
            'unidades' => $unidades,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Configuracion/Unidades/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:unidades,nombre',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Ya existe una unidad con este nombre',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
        ]);

        Unidad::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? $validated['nombre'],
            'activo' => $validated['activo'] ?? true,
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Unidad creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unidad $unit): Response
    {
        return Inertia::render('Configuracion/Unidades/Show', [
            'unidad' => $unit,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unidad $unit): Response
    {
        return Inertia::render('Configuracion/Unidades/Edit', [
            'unidad' => $unit,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unidad $unit): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:unidades,nombre,' . $unit->id,
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Ya existe una unidad con este nombre',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
        ]);

        $unit->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? $validated['nombre'],
            'activo' => $validated['activo'] ?? true,
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Unidad actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unidad $unit): RedirectResponse
    {
        // Verificar si la unidad tiene facturas asociadas
        $facturas = $unit->facturacionMensual()->count();
        
        if ($facturas > 0) {
            return redirect()->route('units.index')
                ->withErrors(['error' => "No se puede eliminar la unidad '{$unit->nombre}' porque tiene {$facturas} registro(s) de facturación asociados."]);
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unidad eliminada correctamente');
    }

    /**
     * Get active units for API/select options
     */
    public function active(): \Illuminate\Http\JsonResponse
    {
        $unidades = Unidad::activas()
            ->select('id', 'nombre', 'descripcion')
            ->orderBy('nombre')
            ->get();

        return response()->json($unidades);
    }
}
