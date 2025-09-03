<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','description','lat','lng','expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Solo reportes no vencidos
    public function scopeActive($q)
    {
        return $q->where(fn($qq) =>
            $qq->whereNull('expires_at')->orWhere('expires_at','>', now())
        );
    }
}
