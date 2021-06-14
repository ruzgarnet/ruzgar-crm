<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Admin controller for global methods
 */
class AdminController extends Controller
{
    use ViewAttributes;

    public function request_routes()
    {
        return [
            'infrastructureLoad' => relative_route('infrastructure.load'),
            'infrastructurePost' => relative_route('infrastructure.post')
        ];
    }
}
