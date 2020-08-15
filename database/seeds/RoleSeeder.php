<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
            'name' => 'Admin',
            'description' => 'Administrator',
            ],
            [
            'name' => 'User',
            'description' => 'Default User',
            ]
        ]);
    }
}
