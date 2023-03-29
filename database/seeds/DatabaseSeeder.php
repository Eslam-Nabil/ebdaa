<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IncomeSeeder::class);
        $this->call(ExpensesSeeder::class);
       // $this->call(UserGroupsTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
    }
}
