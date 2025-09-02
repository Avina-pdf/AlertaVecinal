<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Vista para CREAR un reporte en el mapa
    public function create()
    {
        return view('reports.create');
    }

    // Guardar el reporte
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:120'],
            'description' => ['nullable','string','max:1000'],
            'lat'         => ['required','numeric','between:-90,90'],
            'lng'         => ['required','numeric','between:-180,180'],
        ]);

        $request->user()->reports()->create($data);

        return redirect()
            ->route('reports.map')
            ->with('status', 'Reporte creado correctamente.');
    }

    // Mapa con todos los reportes
    public function map()
    {
        $reports = Report::latest()
            ->take(500)
            ->get(['id','title','lat','lng','created_at']);

        return view('reports.index', compact('reports'));
    }
}
