<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Bumon;
use App\Type;
use Sentinel;
use App\Branch;
use App\Http\Controllers;
use App\PettyCashSystem;
use App\OpeningAmount;
use App\PettyCashTransaction;
use Illuminate\Http\Request;


class InsertDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Database';

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


        $time = Carbon::now()->endOfDay('Asia/Tokyo');
        $next = Carbon::now()->endOfMonth('Asia/Tokyo');


        $totalpayment = PettyCashSystem::where('branch_id', 1)
            ->where('accountant', '1')
            ->where('created_at', 'like', '%' . date("Y-m") . '%')
          ->sum('amount');


        $opening_amount = DB::table('opening_amounts')
            ->where('user_id',12)
            ->get()->first()->opening_amount;



        $balance = $opening_amount - $totalpayment;

        if ($time == $next) {
////                    echo  $time.">=".$next
////                        ;exit;
            $deficiency_amount = $totalpayment;
            $carry_over_amount = $balance;
            $total_received_amount = $deficiency_amount + $deficiency_amount;


            DB::table('petty_cash_transactions')->insert(['balance' => $balance,
                'carry_over_amount' => $carry_over_amount,
                'total_payment' => $totalpayment,
                'total_received_amount' => $total_received_amount,
                'checker_opening_amount' => $opening_amount,
                'deficiency_amount' => $deficiency_amount,
                'branch_id' => 1,


            ]);
//        exit;

        }

        else
            {


                return "not working";
            }




    }
}
