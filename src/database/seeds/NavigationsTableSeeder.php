<?php

namespace Nksoft\Master\databse\seeds;

use Illuminate\Database\Seeder;
use NkSoft\Master\Models\Navigations;

class NavigationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'title' => 'Dashboard',
                'link' => 'dashboard',
                'icon' => 'nav-icon fas fa-tachometer-alt',
                'is_active' => true,
                'order_by' => 1
            ],
            [
                'title' => 'Users',
                'link' => 'users',
                'icon' => 'nav-icon fas fa-users',
                'is_active' => true,
                'order_by' => 99
            ],
            [
                'title' => 'Settings',
                'link' => 'settings',
                'icon' => 'nav-icon fas fa-whmcs',
                'is_active' => true,
                'order_by' => 100
            ],
            [
                'title' => 'Logout',
                'link' => 'logout',
                'icon' => 'nav-icon fas fa-sign-out-alt',
                'is_active' => true,
                'order_by' => 100
            ]
        ];
        foreach ($items as $item) {
            $existItem = Navigations::where(['title' => $item['title']])->first();
            if ($existItem != null) {
                Navigations::create($item);
            } else {
                Navigations::update($item);
            }
        }
    }
}
