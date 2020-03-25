<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CreateUser;

class makeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:userAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user admin';

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
        $controller = new CreateUser(); // make sure to import the controller
        $store = $controller->store();
        if ($store == "TRUE") {
            $this->info("Successfully created user admin");
            $this->info("Username : admin");
            $this->info("Password : 123456");
        } else {
            $this->info($store);
        }
    }
}
