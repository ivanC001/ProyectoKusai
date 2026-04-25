<?php

namespace App\Http\Controllers;

use App\Models\TipoPropiedad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTipoPropiedadController extends Controller
{
    public function index(): View
    {
        $tiposPropiedad = TipoPropiedad::query()
            ->orderBy('nombre')
            ->get();

        return view('admin.tipos-propiedad.index', [
            'tiposPropiedad' => $tiposPropiedad,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:120', 'unique:tipos_propiedad,nombre'],
        ]);

        TipoPropiedad::create([
            'nombre' => trim($validated['nombre']),
        ]);

        return redirect()
            ->route('admin.tipos-propiedad.index')
            ->with('success', 'Tipo de propiedad registrado correctamente.');
    }
}
