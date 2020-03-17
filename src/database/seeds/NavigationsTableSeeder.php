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
                'order_by' => 1,
            ],
            [
                'title' => 'Settings',
                'link' => 'settings',
                'icon' => 'nav-icon fab fa-whmcs',
                'is_active' => true,
                'order_by' => 2,
            ],
            [
                'title' => 'Logout',
                'link' => 'logout',
                'icon' => 'nav-icon fas fa-sign-out-alt',
                'is_active' => true,
                'order_by' => 3,
            ],
        ];
        $items = [
            [
                'title' => 'Dashboard',
                'link' => 'dashboard',
                'icon' => 'nav-icon fas fa-tachometer-alt',
                'is_active' => true,
                'order_by' => 1,
            ],
            [
                'title' => 'System',
                'link' => '#',
                'icon' => '',
                'is_active' => true,
                'order_by' => 90,
                'child' => serialize($system),
            ],
        ];
        Navigations::saveItem($items);
        // foreach ($items as $item) {
        //     $existItem = (['title' => $item['title']])->first();
        //     if ($existItem == null) {
        //         Navigations::create($item);
        //     } else {
        //         $existItem->title = $item['title'];
        //         $existItem->link = $item['link'];
        //         $existItem->icon = $item['icon'];
        //         $existItem->save();
        //     }
        // }
    }
}
