<?php

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('incomes')->truncate();
        Schema::enableForeignKeyConstraints();



        // $incomes = ['Courses', 'Camp Bus', 'Jr.FLL Competition', 'FLL Competition', 'Mineswepper Competition', 'Robo Cub jr Competition', 'Robofest Competition', 'ORCE Competition', 'Robot challenge Competition', 'Lets Make a Robot', 'Make X', 'Prepare For the Future', 'Ramadan Competition', 'Jr.FLL Discovery Competition', 'SeaPersh Competition'];
        $incomes = ['Camp','Camp Bus','Courses','Camp','Jr.FLL Competition','FLL Competition','Mineswepper Competition','Robo Cub jr Competition','Robofest Competition','ORCE Competition','Robot challenge Competition','مسابقة المنتدى الافرو اسيوى','ايجار قاعة','Let\'s Make a Robot','Make X','Prepare For the Future','Ramadan Competition','Jr.FLL Discovery Competition','SeaPersh Competition','مواصلات','ورشة','تيشرتات زى','خامات', 'رحلة'];
        foreach ($incomes as $income) {
            Income::create([
                'title' => $income,
                'isCourse' => $income == 'Courses' ? 1 : 0,
            ]);
        }

    }

}