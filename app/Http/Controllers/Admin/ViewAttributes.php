<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Request;
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
                'header' =>  trans('titles.customers_and_orders'),
                'subRoutes' => ['customers', 'subscriptions']
            ],
            // Customers and Orders Header End

            // Customer Fields
            [
                'title' => trans('tables.customer.title'),
                'route' => 'admin.customers',
                'icon' => 'fas fa-users',
            ],
            [
                'title' => trans('tables.subscription.title'),
                'route' => 'admin.subscriptions',
                'icon' => 'fas fa-wifi',
            ],
            // Customer Field End

            // Customers Applications Header
            [
                'header' =>  trans('titles.customer_applications'),
                'subRoutes' => ['customers_applications', 'customers_application_types']
            ],
            // Customers Applications Header End

            // Customers Applications Field End
            [
                'title' => trans('titles.customer_applications'),
                'route' => 'admin.customer.applications',
                'icon' => 'fas fa-sun'
            ],
            [
                'title' => trans('titles.customer_application_types'),
                'route' => 'admin.customer.application.types',
                'icon' => 'fas fa-moon'
            ],
            // Customers Applications Field End

            // Fault Header
            [
                'header' =>  trans('titles.fault_records'),
                'subRoutes' => ['fault_records', 'fault_record_types']
            ],
            // Fault Header End

            // Fault Fields
            [
                'title' => trans('tables.fault.record.title'),
                'route' => 'admin.fault.records',
                'icon' => 'fas fa-tools',
            ],
            [
                'title' => trans('tables.fault.type.title'),
                'route' => 'admin.fault.types',
                'icon' => 'fas fa-toolbox',
            ],
            // Fault Fields End

            // Message Header
            [
                'header' =>  trans('tables.message.title'),
                'subRoutes' => ['messages']
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
            ],
            // Message Type Fields End

            // Campaings Header
            [
                'header' =>  trans('titles.campaings'),
                'subRoutes' => ['references']
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
                'header' =>  trans('titles.services'),
                'subRoutes' => ['contract_types', 'categories', 'services']
            ],
            // Product and Service Header End

            // Product Fields
            [
                'title' => trans('tables.contract_type.title'),
                'route' => 'admin.contract.types',
                'icon' => 'fas fa-file-signature',
            ],
            [
                'title' => trans('tables.category.title'),
                'route' => 'admin.categories',
                'icon' => 'fas fa-archive',
            ],
            [
                'title' => trans('tables.service.title'),
                'route' => 'admin.services',
                'icon' => 'fas fa-ethernet',
            ],
            // Product Fields End

            // Company Header
            [
                'header' =>  trans('titles.company.title'),
                'subRoutes' => ['dealers', 'staffs', 'roles', 'users']
            ],
            // Company Header End

            // Company Fields
            [
                'title' => trans('tables.dealer.title'),
                'route' => 'admin.dealers',
                'icon' => 'fas fa-store',
            ],
            [
                'title' => trans('tables.staff.title'),
                'route' => 'admin.staffs',
                'icon' => 'fas fa-user-tie',
            ],
            [
                'title' => trans('tables.role.title'),
                'route' => 'admin.roles',
                'icon' => 'fas fa-key',
            ],
            [
                'title' => trans('tables.user.title'),
                'route' => 'admin.users',
                'icon' => 'fas fa-user',
            ]
            // Company Field End
        ];

        // Current route name
        $route = Route::currentRouteName();
        $user = Request::user();

        // Check abilities
        foreach ($data as $key => $item) {
            if (isset($item['subRoutes']) && is_array($item['subRoutes'])) {
                $data[$key]['can'] = $user->permissionGroup($item['subRoutes']);
            } else if (isset($item['route']) && $user->permission($item['route'])) {
                $data[$key]['can'] = true;
            } else {
                $data[$key]['can'] = false;
            }
        }

        // Find active class
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
