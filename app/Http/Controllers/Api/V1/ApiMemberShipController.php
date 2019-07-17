<?php namespace App\Http\Controllers\Api\V1;


use App\Http\Datautil\Util;
use App\Http\Requests\UserRequest;
use App\Mail\Restore;
use App\Models\School;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Mail;
use Reminder;
use Sentinel;
use stdClass;
use URL;
use Validator;
use View;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use File;
use Illuminate\Support\Facades\Input;


class ApiMemberShipController extends Controller
{

    public function index(Request $request)
    {

        $membership = \DB::table('membership_type')
            ->where('status','!=', '2')
            ->select(['id', 'name'])
            ->get();
        return Response::json($membership);
    }

    public function Create(Request $request)
    {
        $ret = array();
        $name = $request->get('membership_type_name', '');
        $membership = \DB::insert('insert into membership_type (name) values (?)', [$name]);

        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }

    public function update(Request $request)
    {
        $ret = array();
        $name = $request->get('membership_type_name', '');
        $type_id = $request->get('type_id', '0');
        $status = $request->get('status', '1');
        $membership_update = \DB::table('membership_type')
                            ->where('id' , $type_id)
                            ->update(['status'=> $status, 'name' => $name]);

        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }
}
