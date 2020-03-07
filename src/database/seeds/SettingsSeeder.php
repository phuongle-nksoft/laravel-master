<?php
namespace Nksoft\Master\databse\seeds;
use Illuminate\Database\Seeder;
use NkSoft\Master\Models\Settings;
class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'title' => 'Nksoft Site',
            'email' => 'nksoft@gmail.com',
            'phone' => '090983524',
            'address' => 'Nguyen Van Troi Street, Cam Duc Town, Khanh Hoa province',
            'description' => 'This is a site default'   
        ]);
    }
}
