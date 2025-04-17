<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('superadmin');

        // $admin = User::create([
        //     'name' => 'rw',
        //     'email' => 'rw@gmail.com',
        //     'password' => bcrypt('password'),
        // ]);

        // $admin->assignRole('rw');

        // $user = User::create([
        //     'name' => 'rt',
        //     'email' => 'rt@gmail.com',
        //     'password' => bcrypt('password'),
        // ]);

        // $user->assignRole('rt');

        // $user = User::create([
        //     'name' => 'warga',
        //     'email' => 'warga@gmail.com',
        //     'password' => bcrypt('password'),
        // ]);

        // $user->assignRole('warga');
    }
}
