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
        $user = $request->user();

        $method = (string)Str::of($request->method())->upper();
        $actions = ['POST', 'PUT', 'DELETE'];

        $auth_routes = ['admin.login', 'admin.login.post'];

        if (!$user && !in_array($route, $auth_routes)) {
            // If the request is from ajax, do not redirect, just print json.
            if (in_array($method, $actions)) {
                return response()->json(['message' => trans('auth.unauthorized')], 401);
            }
            return redirect()->route('admin.login');
        } else if ($user) {
            if (!$user->permission($route) && $route !== 'admin.cant') {
                if (in_array($method, $actions)) {
                    return response()->json([
                        'message' => trans('auth.cant')
                    ], 403);
                }
                return redirect()->route('admin.cant');
            } else if (in_array($route, $auth_routes)) {
                return redirect()->route('admin.dashboard');
            }
        }

        View::share('admin', new AdminController);

        return $next($request);
    }
}
