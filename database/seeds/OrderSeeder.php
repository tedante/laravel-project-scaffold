<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            [
            'name' => 'Admin',
            'email' => 'admin@yopmail.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
            'created_at' => Carbon::now()
            ],
            [
            'name' => 'User',
            'email' => 'user@yopmail.com',
            'password' => Hash::make('user'),
            'role_id' => 2,
            'email_verified_at' => Carbon::now(),
            'is_active' => true,
            'created_at' => Carbon::now()
            ]
        ]);
    }
}
