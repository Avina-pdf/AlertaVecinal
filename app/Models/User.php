<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // para Api token 

class User extends Authenticatable
{
     use HasApiTokens, Notifiable;
    /** Roles  */


  

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];
    public const ROLE_USER  = 'user';
    public const ROLE_ADMIN = 'admin';
    
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function polls()
    {
        return $this->hasMany(Poll::class);
    }

    /** Votos del usuario en encuestas */
    public function pollVotes()
    {
        return $this->hasMany(PollVote::class);
    }


    // app/Models/User.php
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }
    public function reports()
    {
        return $this->hasMany(\App\Models\Report::class);
    }
}
