<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\AdminController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route = Route::currentRouteName();
        $auth_routes = ['admin.login', 'admin.login.post'];
        if (!$request->user() && !in_array($route, $auth_routes)) {
            // If the request is from ajax, do not redirect, just print json.
            if (in_array((string)Str::of($request->method())->upper(), ['POST', 'PUT', 'DELETE'])) {
                return response()->json(['message' => trans('auth.unauthorized')], 401);
            }
            return redirect()->route('admin.login');
        } else if ($request->user() && in_array($route, $auth_routes)) {
            return redirect()->route('admin.dashboard');
        }
        View::share('admin', new AdminController);
        return $next($request);
    }
}
