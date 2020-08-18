<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ComisionesController;
use App\User;
use Carbon\Carbon;

class bonoMatrix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bono:matriz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permire pagar el bono matrix';

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
        $comision = new ComisionesController();
        $users = User::where([
            ['status', '=', 1],
            ['ID', '!=', 1]
        ])->get();
        
        foreach ($users as $user) {
            $comision->bonoMatrix($user->ID);
        }
        $this->info('Bono matrix pagado '. Carbon::now());
    }
}
