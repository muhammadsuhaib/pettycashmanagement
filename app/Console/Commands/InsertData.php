<?php

namespace App\Console\Commands;
namespace App\Console\Commands\DB;
use Illuminate\Console\Command;

use Carbon\Carbon;
use Sentinel;
use App\PettyCashSystem;



class InsertData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Data in Database End of the month';

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

        $opening_amount = DB::table('opening_amounts')
            ->where('user_id',Sentinel::getUser()->id)
            ->get()->first()->opening_amount;

        $carry_over_amount =0;
        $totalpayment = PettyCashSystem::where('branch_id', Sentinel::getUser()->branch_id)
            ->where('accountant', '1')
            ->where('created_at', 'like', '%' . date("Y-m") . '%')
            ->sum('amount');

        $balance = $opening_amount + $carry_over_amount -$totalpayment;

        $time = Carbon::now()->endOfDay('Asia/Tokyo')->setTime('23','59','59');
        $next = Carbon::now()->endOfMonth()->endOfDay('Asia/Tokyo')->setTime('23','59','59');

        if ($time==$next)


        {
            $deficiency_amount = $totalpayment;
            $carry_over_amount = $balance;

            DB::table('petty_cash_transactions')->insert(['balance' => $balance,
                'carry_over_amount' => $carry_over_amount,
                'total_payment' => $totalpayment,
                'total_received_amount' => $opening_amount,
                'checker_opening_amount' => $opening_amount,
                'deficiency_amount' => $deficiency_amount


            ]);


        }

//

    }
}
