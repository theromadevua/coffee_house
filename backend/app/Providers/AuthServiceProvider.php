<?php

namespace App\Providers;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
    ];

    public function boot(): void
    {
        $this->registerPolicies();

       
        Gate::define('admin-only', function (User $user) {
            return $user->isAdmin();
        });
    }
}
