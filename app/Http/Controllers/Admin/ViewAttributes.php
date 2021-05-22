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
            ],
            [
                'title' => trans('tables.dealer.title'),
                'route' => 'admin.dealers',
                'icon' => 'fas fa-store',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.dealers'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.dealer.add'
                    ]
                ]
            ],
            [
                'title' => trans('tables.staff.title'),
                'route' => 'admin.staffs',
                'icon' => 'fas fa-user-tie',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.staffs'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.staff.add'
                    ]
                ]
            ],
            [
                'title' => trans('tables.customer.title'),
                'route' => 'admin.customers',
                'icon' => 'fas fa-users',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.customers'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.customer.add'
                    ]
                ]
            ],
            [
                'title' => trans('tables.category.title'),
                'route' => 'admin.categories',
                'icon' => 'fas fa-box-open',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.categories'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.category.add'
                    ]
                ]
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
