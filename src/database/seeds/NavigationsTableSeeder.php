<?php

namespace Nksoft\Master\database\seeds;

use Illuminate\Database\Seeder;
use Nksoft\Master\Models\Navigations;

class NavigationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $system = [
            [
                'title' => 'Users',
                'link' => 'users',
                'icon' => 'nav-icon fas fa-users',
                'is_active' => true,
                'roles_id' => json_encode([1]),
                'order_by' => 1,
            ],
            [
                'title' => 'Settings',
                'link' => 'settings',
                'icon' => 'nav-icon fab fa-whmcs',
                'is_active' => true,
                'roles_id' => json_encode([1]),
                'order_by' => 2,
            ],
        ];
        $items = [
            [
                'title' => 'System',
                'link' => '#',
                'icon' => '',
                'is_active' => true,
                'order_by' => 90,
                'roles_id' => json_encode([1]),
                'child' => serialize($system),
            ],
            [
                'title' => 'Logout',
                'link' => 'logout',
                'icon' => 'nav-icon fas fa-sign-out-alt',
                'is_active' => true,
                'roles_id' => json_encode([1, 2, 3]),
                'order_by' => 100,
            ],
        ];
        Navigations::saveItem($items);
    }
}
