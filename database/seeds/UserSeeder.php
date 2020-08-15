<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            'name' => 'Admin',
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('admin'),
            'role' => 'Admin',
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
            'created_at' => Carbon::now()
            ],
            [
            'name' => 'User',
            'email' => 'user@yopmail.com',
            'password' => Hash::make('user'),
            'role' => 'User',
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
            'created_at' => Carbon::now()
            ]
        ]);
    }
}
