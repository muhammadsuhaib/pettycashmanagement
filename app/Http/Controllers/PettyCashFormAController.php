<?php

namespace App\Http\Controllers;


use App\PettyCashTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Bumon;
use App\Type;
use Sentinel;
use App\Branch;
use Datatables;

use App\PettyCashSystem;
use Illuminate\Http\Request;

//use Illuminate\Database\Query\Builder;
class PettyCashFormAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        phpinfo();

        if (Sentinel::check() && Sentinel::getUser()->roles()->first()->slug == 'cashier')
        {

//            $time = Carbon::now()->endOfDay('Asia/Tokyo');
//            $next = Carbon::now()->endOfMonth('Asia/Tokyo');
//
//            echo  $time.">=".$next;
//            exit;

        $opening_amount = DB::table('opening_amounts')
            ->where('user_id',Sentinel::getUser()->id)
            ->get()->first()->opening_amount;


            $check_previous_data_count = DB::table('petty_cash_transactions')->where('branch_id', Sentinel::getUser()->branch_id)
                ->get()->count();

            $check_previous_data = DB::table('petty_cash_transactions')->where('branch_id', Sentinel::getUser()->branch_id)
                ->get()->last();
//                echo  $check_previous_data;die();


            if($check_previous_data == null ){
                $checker_status = 0;
            }
            else
                {
                $checker_status = $check_previous_data->checker_status;
            }


            $totalpayment = PettyCashSystem::where('branch_id', Sentinel::getUser()->branch_id)
                ->where('accountant', '1')
                ->where('created_at', 'like', '%' . date("Y-m") . '%')
                ->sum('amount');

            //return dd($totalpayment);


            $carry_over_amount = 0;

            if( $check_previous_data_count > 0){


                //other than first month
                $opening_amount = $check_previous_data->carry_over_amount +  $check_previous_data->deficiency_amount;
                $balance = $check_previous_data->balance;
                $carry_over_amount = $balance;

               // $totalpayment =  $check_previous_data->total_payment;
                $deficiency_payment = $check_previous_data->total_payment;
                $carry_payment = $check_previous_data->carry_over_amount;
            }

            else
                {

                // first month


//                $deficiency_payment = DB::table('petty_cash_transactions')
//                    ->where('checker_status', '1')
//                    ->whereDate('created_at', 'like', '%' . date('Y-m-d', strtotime('last day of last month')) . '%')
//                    ->sum('deficiency_amount');
//
//                $carry_payment = DB::table('petty_cash_transactions')
//                    ->where('checker_status', '1')
//                    ->whereDate('created_at', 'like', '%' . date('Y-m-d', strtotime('last day of last month')) . '%')
//                    ->sum('carry_over_amount');


                $balance = $opening_amount - $totalpayment;

            }






            $pettycash = PettyCashSystem::all();
            $types = Type::all();
            $bumons = DB::table('bumon')->get();
            $transactions = DB::table('petty_cash_transactions')->get();
            $data = PettyCashSystem::where('employee_id', Sentinel::getuser()->id)->orderBy('id', 'desc')->with('branch')->get();

            $branch = Branch::find(Sentinel::getuser()->branch_id);




           // echo '<pre>';print_r($opening_amount);echo '</pre>';die();

            return view('pettycash.forma', compact('data','opening_amount', 'branch', 'bumons', 'types', 'pettycash',
                'totalpayment','checker_status','check_previous_data_count', 'carry_over_amount', 'total_recieved_money', 'balance','transactions','carry_payment','deficiency_payment'));
        }

        else {
            return view ('login.login' );
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $DataEntry = new PettyCashSystem();
//        $DataEntry->request_date=$request->request_date;
        $DataEntry->employee_id = Sentinel::getuser()->id;
        $DataEntry->branch_id = Sentinel::getuser()->branch_id;
//        $DataEntry->total_recieved_money=$request->total_recieved_money;
//        $DataEntry->over_amount=$request->over_amount;
//        $DataEntry->total_payment=$request->total_payment;
        $DataEntry->purchasing_date=$request->purchasing_date;
        $DataEntry->type = $request->type;
        $DataEntry->subject = $request->subject;
        $DataEntry->amount = $request->amount;
        $DataEntry->department = $request->department;
        $DataEntry->note = $request->note;
        $DataEntry->save();

        return back()->with('success', 'Data Successfully Saved :');


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        

        $bumons = DB::table('bumon')->get();
        $data = PettyCashSystem::find($id);
        $types = Type::all();
        return view('pettycash.edit', compact('data', 'types', 'bumons', 'totalpayment', 'balance', 'carry_over_amount', 'total_recieved_money'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $update = PettyCashSystem::find($id);
        $update->branch_id = $request->branch_id;
//        $update->request_date=$request->request_date;
//        $update->total_recieved_money=$request->total_recieved_money;
//        $update->over_amount=$request->over_amount;
//        $update->total_payment=$request->total_payment;
        $update->purchasing_date=$request->purchasing_date;
        $update->type = $request->type;
        $update->subject = $request->subject;
        $update->amount = $request->amount;
        $update->department = $request->department;
        $update->note = $request->note;
        $update->save();

        return redirect('/pettycasha')->with('update', 'Data Successfully updated :)');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        PettyCashSystem::destroy($id);
        return back()->with('delete', 'Data Successfully Deleted :');
    }

    public function getPosts()
    {
        $users = DB::table('petty_cash_systems')->select('*');
        return Datatables::of($users)
            ->make(true);
    }


    public function data_table()
    {

        return view('pettycash.datatable');
//        return datatables()->eloquent(petty_cash_systems::query())->toJson();

//        ->make(true);
    }


}
