<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Models\UserGroup;

class MyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $first_name = $this->ask('What is the first name?');
        $last_name = $this->ask('What is the last name?');
        $email = $this->ask('What is the email address?');
        $password = $this->secret('What is the password?');
    
        $user = new User;

        $user->email = $email;
        $user->password = bcrypt($password);
        $user->name = $first_name.' '.$last_name;
        $user->group_id = 1;

        if ($user->save()) {
            $this->info("User $first_name $last_name was created");
        }
    }
}
