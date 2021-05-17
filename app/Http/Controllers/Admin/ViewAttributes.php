<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

/**
 * Admin controller for global methods
 */
trait ViewAttributes
{
    /**
     * Returns items for sidebar navigation
     *
     * @return array
     */
    public static function sideNav()
    {
        $data = [
            [
                'header' =>  trans('titles.admin.dashboard')
            ],
            [
                'title' => trans('titles.admin.dashboard'),
                'route' => 'admin.dashboard',
                'icon' => 'fas fa-home'
            ]
        ];

        $route = Route::currentRouteName();

        foreach ($data as $key => $item) {
            if (isset($item['submenu'])) {
                $active = false;
                foreach ($item['submenu'] as $subkey => $subnav) {
                    if (isset($subnav['route']) && $subnav['route'] === $route) {
                        $active = true;
                        $data[$key]['submenu'][$subkey]['active'] = true;
                    } else {
                        $data[$key]['submenu'][$subkey]['active'] = false;
                    }
                }
                $data[$key]['active'] = $active;
            } else if (isset($item['route']) && $item['route'] === $route) {
                $data[$key]['active'] = true;
            } else if (isset($item['route'])) {
                $data[$key]['active'] = false;
            }
        }

        return $data;
    }
}
