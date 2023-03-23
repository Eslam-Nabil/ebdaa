<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'group_id' => 1,
            'email' => 'admin@ibdaa.com',
            'password' => bcrypt('admin'),
        ]);
    }
}