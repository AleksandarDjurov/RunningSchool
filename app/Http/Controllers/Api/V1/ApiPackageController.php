<?php namespace App\Http\Controllers\Api\V1;


use App\Http\Datautil\Util;
use App\Http\Requests\UserRequest;
use App\Mail\Restore;
use App\Models\Package;
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


class ApiPackageController extends Controller
{

    public function index(Request $request)
    {

        $membership = \DB::table('packages')
            ->where('status','!=', '2')
            ->select(['id', 'name'])
            ->get();
        return Response::json($membership);
    }

    public function Create(Request $request)
    {
        $ret = array();

        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/packages/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/packages/'.$safeName;
        }

        $package = Package::create([
            'name'      => $request->input('name'),
            'pic'       => $request->input('pic'),
            'stock'     => $request->input('stock'),
        ]);

        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }

    public function update(Request $request)
    {
        $ret = array();
        $id = $request->get('id', '0');
        $package = Package::find($id);
        $package->update($request->except('pic_file'));
        // is new image for school uploaded?
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/packages/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            //delete old pic if exists
            if (File::exists(public_path() . $package->pic)) {
                File::delete(public_path() . $package->pic);
            }
            //save new file path into db
            $package->pic = '/uploads/packages/'.$safeName;
        }
        $package->save();

        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        return Response::json($ret);
    }
}
