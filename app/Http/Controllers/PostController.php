<?php
//
//
//
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Datatables;
//use DB;
//
//
//class PostController extends Controller
//{
//    /**
//     * Show the application dashboard.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function datatable()
//    {
//        return view('datatable');
//    }
//
//    /**
//     * Show the application dashboard.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function getPosts()
//    {
//        $users = DB::table('demo_posts')->select('*');
//        return Datatables::of($users)
//            ->make(true);
//    }
//}

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Datatables;
use DB;

use App\PettyCashTransaction;
use Carbon\Carbon;
use App\Bumon;
use App\Type;
use Sentinel;
use App\Branch;
use App\PettyCashSystem;
//use Yajra\DataTables\DataTablesServiceProvider;
//use Yajra\DataTables\Facades\DataTables;


class PostController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()


    {
        if (Sentinel::getUser()->roles()->first()->slug == 'checker'|| Sentinel::getUser()->roles()->first()->slug == 'accountant') {


            $branch = Branch::find(Sentinel::getuser()->branch_id);


            $data = PettyCashSystem::where('employee_id', Sentinel::getuser()->id)->orderBy('id', 'desc')->with('branch')->get();

            return view('datatable', compact('data', 'branch'));

        }
        else

            {

            return redirect('/');

            }
        }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPosts()
    {
//        ini_set('memory_limit', '700M');
        $users = DB::table('petty_cash_systems')->select('*');
//        $output = array();
//        $segmentData = $users->get();
//        foreach($segmentData as $segment){
//            $output[] =array((string)'checker', (string)$segment->checker);
//        }
//        header("Content-type: application/json");
//        echo json_encode($output);
//      echo "<pre>";
//        print_r($users->get());
//        exit;
        return Datatables::of($users)
            ->make(true);
    }
}