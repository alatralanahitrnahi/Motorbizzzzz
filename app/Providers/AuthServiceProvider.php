<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Warehouse;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\WarehouseBlockPolicy;
use App\Models\WarehouseBlock;
use App\Models\Material;
use App\Policies\MaterialPolicy;
use App\Models\PurchaseOrder;
use App\Policies\PurchaseOrderPolicy;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
   protected $policies = [
    User::class => UserPolicy::class,
    Warehouse::class => WarehousePolicy::class,
    Material::class => MaterialPolicy::class,
    PurchaseOrder::class => PurchaseOrderPolicy::class, // ✅ Add this line
];


    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Optional: Define gates if you prefer gates over policies
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });
    
      // Define the manage-warehouses gate that your routes are checking for
        Gate::define('manage-warehouses', function (User $user) {
            return in_array($user->role, ['admin', 'inventory_manager']);
        });

        // Optional: You can also define other gates if needed
        Gate::define('manage-inventory', function (User $user) {
            return in_array($user->role, ['admin', 'inventory_manager', 'warehouse_staff']);
        });

        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });
      
  
}
}