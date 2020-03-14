<?php

namespace Nksoft\Master\databse\seeds;

use Illuminate\Database\Seeder;
use Nksoft\Master\Models\Roles;
use Nksoft\Master\Models\Users;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesExist = Roles::get();
        if ($rolesExist != null) {
            $roles = [
                [
                    'name' => 'Administrator',
                    'is_active' => true
                ],
                [
                    'name' => 'Browser',
                    'is_active' => true
                ],
                [
                    'name' => 'Developer',
                    'is_active' => true
                ],
            ];
            Roles::createMany($roles);
            $dev = Roles::where(['name' => 'Developer'])->first();
            if ($dev != null) {
                Users::create([
                    'name' => 'admin',
                    'email' => 'info@nksoft-vn.com',
                    'password' => Hash::make('admin@123'),
                    'phone' => '0909838524',
                    'role_id' => $dev->id
                ]);
            }
        }
    }
}
