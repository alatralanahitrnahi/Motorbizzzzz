<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\MaterialRequest;

class NotificationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            // Share unread notification count
            $unreadCount = auth()->user()->notifications()
                ->where('type', 'dashboard')
                ->whereNull('read_at')
                ->count();

            View::share('unreadNotificationCount', $unreadCount);

            // Share pending material requests (for admin only)
            if (auth()->user()->isAdmin()) {
                $pendingMaterials = MaterialRequest::with('requestedBy')
                    ->where('resolved', false)
                    ->latest()
                    ->get();

                View::share('pendingMaterials', $pendingMaterials);
            }
        }

        return $next($request);
    }
}
