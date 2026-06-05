<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if the user is authenticated and has a permission_id, then check the permissions
        if (Auth::check() && Auth::user()->permission_id) {
            
            // database form the permission_id, find the corresponding permission record
            $activeRole = Permission::find(Auth::user()->permission_id);

            if ($activeRole && $activeRole->permission) {
                // JSON permission data to associative array
                $permissions = json_decode($activeRole->permission, true);

                // Check permission conditions
                if (
                    // Services Module
                    (empty($permissions['services']['list']) && Route::is('admin.services')) ||
                    (empty($permissions['services']['add']) && Route::is('admin.servicesCreate')) ||
                    (empty($permissions['services']['add']) && Route::is('admin.servicesUpdate')) ||
                    (empty($permissions['services']['delete']) && Route::is('admin.servicesDelete')) ||

                    // Services Others
                    (empty($permissions['servicesOthers']['category']) && $request->is('admin/services/categories*')) ||

                    // Admin Users Module
                    (empty($permissions['adminUsers']['list']) && Route::is('admin.adminUsers')) ||
                    (empty($permissions['adminUsers']['add']) && Route::is('admin.adminUsersCreate')) ||
                    (empty($permissions['adminUsers']['add']) && Route::is('admin.adminUsersPost')) ||
                    (empty($permissions['adminUsers']['suspend']) && Route::is('admin.adminUsersSuspend')) ||

                    // Admin Roles Module
                    (empty($permissions['adminRoles']['list']) && Route::is('admin.userRoles')) ||
                    (empty($permissions['adminRoles']['add']) && Route::is('admin.userRoleAction')) ||
                    (empty($permissions['adminRoles']['delete']) && Route::is('admin.usersRolesDelete'))
                ) {
                    // if the user doesn't have the required permission, show a 401 Unauthorized error
                    return abort('401');
                }
            }
        }

        return $next($request);
    }
}