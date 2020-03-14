<?php

namespace Nksoft\Master\database\seeds;

use Illuminate\Database\Seeder;
use Nksoft\Master\Models\Settings;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'title' => 'Nksoft Control panel',
            'email' => 'info@nksoft-vn.com',
            'phone' => '090983524',
            'address' => 'Nguyen Van Troi Street, Cam Duc Town, Khanh Hoa province',
            'description' => 'This is a site default'
        ]);
    }
}
