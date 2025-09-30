<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return Report::active()
            ->latest()
            ->take(500)
            ->get(['id','title','description','lat','lng','created_at','expires_at']);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title' => ['required','string','max:120'],
            'description' => ['nullable','string','max:1000'],
            'lat' => ['required','numeric','between:-90,90'],
            'lng' => ['required','numeric','between:-180,180'],
            'ttl' => ['required','in:6h,24h,3d,7d'],
        ]);
        $ttlHours = ['6h'=>6,'24h'=>24,'3d'=>72,'7d'=>168][$data['ttl']];

        $report = $r->user()->reports()->create([
            'title'=>$data['title'],
            'description'=>$data['description'] ?? null,
            'lat'=>$data['lat'],
            'lng'=>$data['lng'],
            'expires_at'=>now()->addHours($ttlHours),
        ]);

        return response()->json($report, 201);
    }
}
