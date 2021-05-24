<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

/**
 * View attributes for admin
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
            // Header Fields
            [
                'header' =>  trans('titles.admin.dashboard')
            ],
            // Header Field End

            // Admin Fields
            [
                'title' => trans('titles.admin.dashboard'),
                'route' => 'admin.dashboard',
                'icon' => 'fas fa-home'
            ],
            // Admin Field End

            // Dealer Fields
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
            // Dealer Field End

            // Staff Fields
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
            // Staff Field End

            // User Fields
            [
                'title' => trans('tables.user.title'),
                'route' => 'admin.users',
                'icon' => 'fas fa-user',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.users'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.user.add'
                    ]
                ]
            ],
            // User Field End

            // Customer Fields
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
            // Customer Field End

            // Contract Type Fields
            [
                'title' => trans('tables.contract_type.title'),
                'route' => 'admin.contract.types',
                'icon' => 'fas fa-file-signature',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.contract.types'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.contract.type.add'
                    ]
                ]
            ],
            // Contract Type Field End

            // Category Fields
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
            ],
            // Category Field End

            // Product Fields
            [
                'title' => trans('tables.product.title'),
                'route' => 'admin.products',
                'icon' => 'fas fa-shopping-cart',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.products'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.product.add'
                    ]
                ]
            ],
            // Product Field End

            // Service Fields
            [
                'title' => trans('tables.service.title'),
                'route' => 'admin.services',
                'icon' => 'fas fa-box',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.services'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.service.add'
                    ]
                ]
            ],
            // Service Field End

            // Message Fields
            [
                'title' => trans('tables.message.title'),
                'route' => 'admin.messages',
                'icon' => 'fas fa-sms',
                'submenu' => [
                    [
                        'title' => trans('titles.list'),
                        'route' => 'admin.messages'
                    ],
                    [
                        'title' => trans('titles.add'),
                        'route' => 'admin.message.add'
                    ]
                ]
            ]
            // Message Field End

        ];

        // Find active routes for view
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
