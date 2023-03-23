<?php

use Illuminate\Database\Seeder;

class UserGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_groups')->insert([
            'name' => 'admins',
        ]);

        DB::table('user_groups')->insert([
            'name' => 'secretary',
        ]);

        DB::table('user_groups')->insert([
            'name' => 'marketing',
        ]);

        DB::table('user_groups')->insert([
            'name' => 'coaches',
        ]);

        DB::table('user_groups')->insert([
            'name' => 'finance',
        ]);
    }
}
