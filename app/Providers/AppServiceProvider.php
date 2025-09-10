<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate; // opcional si defines Gates manuales

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Poll::class => \App\Policies\PollPolicy::class,
        Post::class => PostPolicy::class, 
        
        // Ejemplos para el futuro:
        // \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
        // \App\Models\Report::class  => \App\Policies\ReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // En Laravel 10/11 no necesitas llamar a registerPolicies()
        // pero si quieres puedes dejarlo:
        // $this->registerPolicies();

        // Gate global opcional (ejemplo):
        // Gate::before(fn ($user, $ability) => $user->is_admin ? true : null);
    }
}
