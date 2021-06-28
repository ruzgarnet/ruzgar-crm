<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/payment/result/*',
        '/infrastructure/*',
        '/payment/auto/result',
        '/payment/pre/auth/result/*',
        '/payment/get',
        '/payment/pay'
    ];
}
