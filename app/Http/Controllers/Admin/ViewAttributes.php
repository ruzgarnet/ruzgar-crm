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
            // Admin Fields
            [
                'title' => trans('titles.admin.dashboard'),
                'route' => 'admin.dashboard',
                'icon' => 'fas fa-home'
            ],
            [
                'title' => trans('titles.infrastructure'),
                'route' => 'admin.infrastructure',
                'icon' => 'fas fa-file'
            ],

            // Customers and Orders Header
            [
                'header' =>  trans('titles.customers_and_orders')
            ],
            // Customers and Orders Header End

            // Customer Fields
            [
                'title' => trans('tables.customer.title'),
                'route' => 'admin.customers',
                'icon' => 'fas fa-users',
                'actions' => [
                    'add' => 'admin.customer.add'
                ]
            ],
            [
                'title' => trans('tables.subscription.title'),
                'route' => 'admin.subscriptions',
                'icon' => 'fas fa-wifi',
                'actions' => [
                    'add' => 'admin.subscription.add'
                ]
            ],
            // Customer Field End

            // Customers Applications Header
            [
                'header' =>  trans('titles.customer_applications')
            ],
            // Customers Applications Header End

            // Customers Applications Field End
            [
                'title' => trans('titles.customer_applications'),
                'route' => 'admin.customer_applications',
                'icon' => 'fas fa-sun'
            ],
            [
                'title' => trans('titles.customer_application_types'),
                'route' => 'admin.customer_application_types',
                'icon' => 'fas fa-moon'
            ],
            // Customers Applications Field End

            // Customers and Orders Header
            [
                'header' =>  trans('titles.fault_records')
            ],
            // Customers and Orders Header End

            // Fault Fields
            [
                'title' => trans('tables.fault.record.title'),
                'route' => 'admin.fault.records',
                'icon' => 'fas fa-tools',
                'actions' => [
                    'add' => 'admin.fault_record.add'
                ]
            ],
            [
                'title' => trans('tables.fault.type.title'),
                'route' => 'admin.fault.types',
                'icon' => 'fas fa-toolbox',
                'actions' => [
                    'add' => 'admin.fault_type.add'
                ]
            ],
            // Fault Fields End

            // Campaings Header
            [
                'header' =>  trans('titles.campaings')
            ],
            // Campaings Header End

            // Campaing Field
            [
                'title' => trans('tables.reference.title'),
                'route' => 'admin.references',
                'icon' => 'fas fa-people-arrows'
            ],
            // Campaing Field End

            // Product and Service Header
            [
                'header' =>  trans('titles.services')
            ],
            // Product and Service Header End

            // Product Fields
            [
                'title' => trans('tables.contract_type.title'),
                'route' => 'admin.contract.types',
                'icon' => 'fas fa-file-signature',
                'actions' => [
                    'add' => 'admin.contract.type.add'
                ]
            ],
            [
                'title' => trans('tables.category.title'),
                'route' => 'admin.categories',
                'icon' => 'fas fa-archive',
                'actions' => [
                    'add' => 'admin.category.add'
                ]
            ],
            [
                'title' => trans('tables.service.title'),
                'route' => 'admin.services',
                'icon' => 'fas fa-ethernet',
                'actions' => [
                    'add' => 'admin.service.add'
                ]
            ],
            // Product Fields End

            // Company Header
            [
                'header' =>  trans('titles.company.title')
            ],
            // Company Header End

            // Company Fields
            [
                'title' => trans('tables.dealer.title'),
                'route' => 'admin.dealers',
                'icon' => 'fas fa-store',
                'actions' => [
                    'add' => 'admin.dealer.add'
                ]
            ],
            [
                'title' => trans('tables.staff.title'),
                'route' => 'admin.staffs',
                'icon' => 'fas fa-user-tie',
                'actions' => [
                    'add' => 'admin.staff.add'
                ]
            ],
            [
                'title' => trans('tables.user.title'),
                'route' => 'admin.users',
                'icon' => 'fas fa-user',
                'actions' => [
                    'add' => 'admin.user.add'
                ]
            ],
            // Company Field End

            // Product and Service Header
            // [
            //     'header' =>  trans('titles.other')
            // ],
            // Product and Service Header End

            // Message Header
            [
                'header' =>  trans('tables.message.title')
            ],
            // Message Header End

            // Message Fields
            [
                'title' => trans('tables.message.send_sms'),
                'route' => 'admin.message.send',
                'icon' => 'fas fa-paper-plane'
            ],
            // Message Field End

            // Message Type Fields
            [
                'title' => trans('tables.message.alt_title'),
                'route' => 'admin.messages',
                'icon' => 'fas fa-sms'
            ]
            // Message Type Fields End
        ];

        // Find active routes for view
        $route = Route::currentRouteName();

        foreach ($data as $key => $item) {
            if (isset($item['submenu'])) {
                $active = false;
                foreach ($item['submenu'] as $subkey => $subnav) {
                    if (isset($subnav['route']) && $subnav['route'] == $route) {
                        $active = true;
                        $data[$key]['submenu'][$subkey]['active'] = true;
                    } else {
                        $data[$key]['submenu'][$subkey]['active'] = false;
                    }
                }
                $data[$key]['active'] = $active;
            } else if (isset($item['route']) && $item['route'] == $route) {
                $data[$key]['active'] = true;
            } else if (isset($item['route'])) {
                $data[$key]['active'] = false;
            }
        }

        return $data;
    }
}
