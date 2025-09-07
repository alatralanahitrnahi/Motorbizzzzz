<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;
use App\Models\Module;
use App\Models\PurchaseOrder;
use App\Observers\PurchaseOrderObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Register observer
        PurchaseOrder::observe(PurchaseOrderObserver::class);

        // 2. Share all warehouses globally to views
        View::composer('*', function ($view) {
            $view->with('allWarehouses', Warehouse::all());
        });

        // 3. Share navigation items dynamically based on permissions
        View::composer('*', function ($view) {
            $user = Auth::user();
            $navigationItems = [];

            if ($user) {
                $modules = Module::where('is_active', 1)->get();

                foreach ($modules as $module) {
                    $permission = $this->getPermissionFromRoute($module->route);

                    if ($this->userHasPermission($user, $permission)) {
                        $navigationItems[$permission] = [
                            'title' => $module->name,
                            'route' => $module->route,
                            'icon' => $module->icon,
                            'section' => $this->getSectionName($module->route),
                        ];
                    }
                }

                // Always show dashboard
                $navigationItems['dashboard'] = [
                    'title' => 'Dashboard',
                    'route' => 'dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'section' => 'Home',
                ];

                // Always show reports
                $navigationItems['reports'] = [
                    'title' => 'Reports',
                    'route' => 'reports.index',
                    'icon' => 'fas fa-file-alt',
                    'section' => 'Reports',
                ];
            }

            $view->with('navigationItems', $navigationItems);
        });

        // 4. Share permission checking function globally
      \Illuminate\Support\Facades\Blade::if('canAccess', function ($action, $module) {
    $user = auth()->user();

    if (!$user) return false;

    if (in_array($user->role, ['admin', 'super_admin'])) {
        return true;
    }

    $moduleIds = [
        'materials' => 4,
        'vendors' => 5,
        'warehouses' => 2,
        'users' => 1,
        'blocks' => 3,
        'quality-analysis' => 6,
        'purchase-orders' => 7,
        'inventory' => 8,
        'barcode' => 9,
        'reports' => 10,
    ];

    $validActions = ['view', 'edit', 'create', 'delete', 'assign'];
    if (!in_array($action, $validActions)) return false;

    $moduleId = $moduleIds[$module] ?? null;
    if (!$moduleId) return false;

    $permissionColumn = 'can_' . $action;

    return \DB::table('permissions')
        ->where('user_id', $user->id)
        ->where('module_id', $moduleId)
        ->where($permissionColumn, 1)
        ->exists();
});

    }

    private function userHasPermission($user, $permission): bool
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        $hasAnyPermissions = DB::table('permissions')
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAnyPermissions) {
            return false;
        }

        $module = Module::where('route', 'dashboard.' . $permission)
            ->orWhere('route', $permission)
            ->first();

        if (!$module) {
            return false;
        }

        return $user->hasModulePermission($module->id, 'view');
    }

    private function getPermissionFromRoute(string $route): string
    {
        return str_replace(['dashboard.', '.index'], '', $route);
    }

    private function getSectionName(string $route): string
    {
        if (str_contains($route, 'users') || str_contains($route, 'vendors')) {
            return 'User & Vendor Management';
        }
        if (str_contains($route, 'warehouses') || str_contains($route, 'blocks')) {
            return 'Warehouse Management';
        }
        if (str_contains($route, 'materials') || str_contains($route, 'inventory') || str_contains($route, 'barcode')) {
            return 'Inventory & Materials';
        }
        if (str_contains($route, 'purchase-orders')) {
            return 'Procurement';
        }
        if (str_contains($route, 'quality-analysis')) {
            return 'Quality Control';
        }
        if (str_contains($route, 'reports')) {
            return 'Reports';
        }

        return 'General';
    }
}   