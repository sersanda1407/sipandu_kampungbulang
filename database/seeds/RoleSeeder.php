<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'superadmin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'rw',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'rt',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'warga',
            'guard_name' => 'web'
        ]);
    }
}
