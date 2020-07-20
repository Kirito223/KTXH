<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Auth;
use App\tbl_route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('quanly-taikhoan', function($user) {
            return $user->hasPermission('QLTaikhoan');
        });
		
		Gate::before(function ($user) {
            return $user->isSuperAdmin() ? true : null;
        });
		
		Gate::define('super-admin', function($user) {
            return $user->isSuperAdmin();
        });
		
		$routes = tbl_route::all();
        foreach($routes as $route) {
        Gate::define($route->route, function($user) use ($route){
            return $user->hasPermissionBasedOnRoute($route->route);
        }); 
        }
    }
}
