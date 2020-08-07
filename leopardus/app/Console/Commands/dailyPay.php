<?php

namespace App\Console\Commands;

use App\Http\Controllers\ComisionesController;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class dailyPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite pagar los bonos del sistema';

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

        $comision->bonoDirecto();
        $this->info('Bono directo pagado '. Carbon::now());
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
