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
            // Main Fields
            [
                'title' => trans('titles.admin.dashboard'),
                'route' => 'admin.dashboard',
                'icon' => 'fas fa-home'
            ],
            [
                'title' => trans('titles.infrastructure'),
                'route' => 'admin.infrastructure',
                'icon' => 'fas fa-network-wired'
            ],
            // Main Fields End

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

            // Support Header
            [
                'header' =>  trans('titles.support'),
                'subRoutes' => ['messages', 'customers_applications', 'fault_records']
            ],
            // Support Header End

            // Support Field End
            [
                'title' => trans('tables.message.send_sms'),
                'route' => 'admin.message.send',
                'icon' => 'fas fa-paper-plane'
            ],
            [
                'title' => trans('titles.customer_applications'),
                'route' => 'admin.customer.applications',
                'icon' => 'fas fa-clipboard'
            ],
            [
                'title' => trans('tables.fault.record.title'),
                'route' => 'admin.fault.records',
                'icon' => 'fas fa-tools',
            ],
            // Message Field End

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

            // Payments Header
            [
                'header' =>  trans('tables.payment.singular'),
                'subRoutes' => ['payments', 'report']
            ],
            // Payments Header End

            // Payments Fields
            [
                'title' => trans('tables.payment.plural'),
                'route' => 'admin.payments',
                'icon' => 'fas fa-lira-sign',
            ],
            [
                'title' => trans('tables.payment.monthly'),
                'route' => 'admin.payment.monthly',
                'icon' => 'fas fa-calendar-alt',
            ],
            [
                'title' => trans('tables.payment.penalty'),
                'route' => 'admin.payment.penalties',
                'icon' => 'fas fa-calendar-times',
            ],
            [
                'title' => trans('tables.main.report'),
                'route' => 'admin.report',
                'icon' => 'fas fa-folder-open',
            ],
            // Payments Fields End

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

            // System Header
            [
                'header' =>  trans('titles.system'),
                'subRoutes' => ['customer_application_types', 'fault_types', 'messages', 'request_messages']
            ],
            // System Header End

            // System Fields
            [
                'title' => trans('titles.request_messages'),
                'route' => 'admin.request.messages',
                'icon' => 'fas fa-level-up-alt'
            ],
            [
                'title' => trans('titles.customer_application_types'),
                'route' => 'admin.customer.application.types',
                'icon' => 'fas fa-clipboard'
            ],
            [
                'title' => trans('tables.fault.type.title'),
                'route' => 'admin.fault.types',
                'icon' => 'fas fa-toolbox',
            ],
            [
                'title' => trans('tables.message.alt_title'),
                'route' => 'admin.messages',
                'icon' => 'fas fa-sms'
            ],
            // System Fields End

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
