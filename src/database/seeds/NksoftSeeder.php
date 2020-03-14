<?php

namespace Nksoft\Master\database\seeds;

use Illuminate\Database\Seeder;

class NksoftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsTableSeeder::class);
        $this->call(NavigationsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
