<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => 'admin',
        //     'group_id' => 1,
        //     'email' => 'admin@ibdaa.com',
        //     'password' => bcrypt('admin'),
        // ]);

        DB::table('users')->insert([
            'name' => 'Expense',
            'group_id' => 6,
            'email' => 'expense@ibdaa.com',
            'password' => bcrypt('123123'),
        ]);

    }
}