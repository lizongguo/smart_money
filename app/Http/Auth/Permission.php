<?php

namespace App\Http\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Http\Middleware\Pjax;

class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     *
     * @return true
     */
    public static function check($permission)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                call_user_func([Permission::class, 'check'], $permission);
            });

            return;
        }

        if (Auth::guard('admin')->user()->cannotPermission($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function allow($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (!Auth::guard('admin')->user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Don't check permission.
     *
     * @return bool
     */
    public static function free()
    {
        return true;
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function deny($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (Auth::guard('admin')->user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     */
    public static function error()
    {
        $response = response(view('admin.pages.error', ['error' => '没有权限访问当前页面!']));
        
        if (!request()->pjax() && request()->ajax()) {
            abort(403, '你没有权限访问当前页面。!');
        }
        Pjax::respond($response);
    }

    /**
     * If current user is administrator.
     *
     * @return mixed
     */
    public static function isAdministrator()
    {
        return Auth::guard('admin')->user()->isRole('administrator');
    }
    
    /**
     * Send a response through this middleware.
     *
     * @param Response $response
     */
    public static function respond($response)
    {
        $next = function () use ($response) {
            return $response;
        };
        
        (new static())->handle(Request::capture(), $next)->send();

        exit;
    }
}
