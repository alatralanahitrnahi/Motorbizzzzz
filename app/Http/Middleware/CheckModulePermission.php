<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, $moduleRoute, $permission = 'view')
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Admin has full access
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Find module by route
        $module = Module::where('route', $moduleRoute)->first();
        
        if (!$module || !$user->hasModulePermission($module->id, $permission)) {
            abort(403, 'You do not have permission to access this module.');
        }

        return $next($request);
    }
}