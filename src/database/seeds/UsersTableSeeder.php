<?php

namespace Nksoft\Master\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Nksoft\Master\Models\Roles;
use Nksoft\Master\Models\Users;

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
                    'is_active' => true,
                ],
                [
                    'name' => 'Browser',
                    'is_active' => true,
                ],
                [
                    'name' => 'Developer',
                    'is_active' => true,
                ],
            ];
            foreach ($roles as $item) {
                Roles::create($item);
            }
            $dev = Roles::where(['name' => 'Developer'])->first();
            if ($dev != null) {
                Users::create([
                    'name' => 'admin',
                    'email' => 'info@codev.vn',
                    'password' => Hash::make('admin@123'),
                    'phone' => '0909838524',
                    'role_id' => $dev->id,
                ]);
            }
        }
    }
}
