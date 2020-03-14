<?php

namespace Nksoft\Master\databse\seeds;

use Illuminate\Database\Seeder;
use NkSoft\Master\Models\Settings;

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
