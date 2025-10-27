<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-profile', function ($user, $profileUser = null) {
            // Users can always view their own profile
            if ($profileUser && $user->id === $profileUser->id) {
                return true;
            }
            // Admins can view any profile
            return $user->role === 'admin' || $user->role === 'super-admin';
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin' || $user->role === 'super-admin';
        });
    }
}
