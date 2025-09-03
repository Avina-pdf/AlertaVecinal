<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Si arreglaste tu Controller base, puedes dejar el constructor; si no, quítalo.
    // public function __construct()
    // {
    //     $this->middleware(['auth','verified']);
    // }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','max:120'],
            'description' => ['nullable','string','max:1000'],
            'lat'         => ['required','numeric','between:-90,90'],
            'lng'         => ['required','numeric','between:-180,180'],
            'ttl'         => ['required','in:6h,24h,3d,7d'],
        ]);

        $ttlHours = ['6h'=>6, '24h'=>24, '3d'=>72, '7d'=>168][$data['ttl']];

        $request->user()->reports()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'lat'         => $data['lat'],
            'lng'         => $data['lng'],
            'expires_at'  => now()->addHours($ttlHours),
        ]);

        return redirect()->route('reports.map')->with('status', 'Reporte creado.');
    }

    public function map()
    {
        $reports = Report::active()
            ->latest()
            ->take(500)
            ->get(['id','title','description','lat','lng','created_at','expires_at']);

        return view('reports.index', compact('reports'));
    }
}
