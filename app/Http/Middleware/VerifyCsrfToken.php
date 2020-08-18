<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'send_mail/*',
    ];

    public function handle($request, Closure $next)
    {
        // 使用CSRF
        return parent::handle($request, $next);
        // 禁用CSRF
        //return $next($request);
    }
}
