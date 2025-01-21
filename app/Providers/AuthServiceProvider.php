<?php

namespace App\Providers;

use App\Models\Report;
use App\Policies\ReportPolicy;
use App\Auth\Md5PasswordHasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Report::class => ReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        Hash::extend('md5', function () {
            return new Md5PasswordHasher();
        });
    }

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Definiere zusätzliche Gates für spezifische Berechtigungen
        Gate::define('manage-users', function ($user) {
            return $user->role === 'lawyer';
        });
    }
}
