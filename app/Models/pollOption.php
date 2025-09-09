<?php
// app/Models/PollOption.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = ['poll_id','text','position'];

    public function poll(){ return $this->belongsTo(Poll::class); }
    public function votes(){ return $this->hasMany(PollVote::class); }
}
