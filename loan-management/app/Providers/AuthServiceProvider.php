<?php

namespace App\Providers;

use App\Models\Loan;
use App\Policies\LoanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Register your policies here
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Loan::class => LoanPolicy::class,
    ];    

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define any Gates or other authorization logic here, if needed
    }
}
