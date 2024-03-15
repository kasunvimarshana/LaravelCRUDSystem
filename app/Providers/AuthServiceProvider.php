<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
// use Laravel\Passport\Passport;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        // \App\Models\Model::class => \App\Policies\ModelPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        // $this->registerPolicies();
        // Gate::define('action', [ModelPolicy::class, 'action']);

        Gate::define('read-records', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner", "manager", "cashier");
            return in_array($user->role, $whitelist_roles);
        });

        Gate::define('create-records', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner");
            return in_array($user->role, $whitelist_roles);
        });

        Gate::define('update-records', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner", "manager", "cashier");
            return in_array($user->role, $whitelist_roles);
        });

        Gate::define('soft-delete-records', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner", "manager");
            return in_array($user->role, $whitelist_roles);
        });

        Gate::define('permanently-delete-records', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner");
            return in_array($user->role, $whitelist_roles);
        });

        Gate::define('create-customer-purchase-medication', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner", "manager", "cashier");
            return in_array($user->role, $whitelist_roles);
        });

        Gate::define('create-customer-return-medication', function (User $user) {
            if (!$user) return false;
            $whitelist_roles = array("owner", "manager", "cashier");
            return in_array($user->role, $whitelist_roles);
        });
    }
}

