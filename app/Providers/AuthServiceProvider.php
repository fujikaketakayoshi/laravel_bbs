<?php

namespace App\Providers;

use App\Models\Thread;
use App\Models\Reply;
use App\Policies\ThreadPolicy;
use App\Policies\ReplyPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class => ReplyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('admin', function($user){
            return ($user->role == 1);
        });
        Gate::define('verified', function($user){
            return ($user->email_verified_at);
        });

    }
}
