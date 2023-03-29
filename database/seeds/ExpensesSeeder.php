<?php

use App\Models\Expenses;
use Illuminate\Database\Seeder;

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('expenses')->truncate();
        Schema::enableForeignKeyConstraints();

        $expenses = ['Courses','Supply','Camp Bus','Jr.FLL Competition','FLL Competition','Mineswepper Competition','Robofest Competition','ORCE Competition','Robot challenge Competition','Lets Make a Robot','Make X','Jr.FLL Discovery Competition','SeaPersh Competition','Robo Cub jr Competition'];
        foreach ($expenses as $expense) {
            Expenses::create([
                'title' => $expense,
                'isSupply' => $expense == 'Supply' ? 1 : 0,
            ]);
        }        

    }

}