<?php

// app/Models/Poll.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','description','closes_at','is_closed'];
    protected $casts = ['closes_at'=>'datetime','is_closed'=>'boolean'];

    public function user(){ return $this->belongsTo(User::class); }
    public function options(): HasMany { return $this->hasMany(PollOption::class)->orderBy('position'); }
    public function votes(): HasMany { return $this->hasMany(PollVote::class); }

    public function scopeActive($q){
        return $q->where('is_closed', false)
                 ->where(function($qq){
                    $qq->whereNull('closes_at')->orWhere('closes_at','>', now());
                 });
    }

    public function getTotalVotesAttribute(): int {
        return $this->votes_count ?? $this->votes()->count();
    }
}

