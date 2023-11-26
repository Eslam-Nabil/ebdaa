<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $user = request()->user();
            if($user){
            $wallet = $user->wallet;
            $total_in = $wallet->where('type', 'in')->sum('amount');
            $total_out = $wallet->where('type', 'out')->sum('amount');
            $view->with(['user'=> $user,'total_in'=>$total_in ,'total_out'=>$total_out]);
        }else{
                $view->with('user', $user);

            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
