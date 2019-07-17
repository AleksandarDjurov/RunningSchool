<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use File;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use Yajra\DataTables\DataTables;
use Validator;
Use App\Mail\Restore;
use stdClass;
use App\Http\Datautil\Util;
use App\Models\Package;


class PackageController extends JoshController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index()
    {

        // Show the page
        return view('admin.package.index', compact('schools'));
    }
    
    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function data()
    {         
      
        $packages = Package::all();        
      
        return DataTables::of($packages)
            // ->editColumn('created_at',function($school) {
            //     return $school->created_at->diffForHumans();
            // })
             ->editColumn('pic',function($package) {
                $pic = '<img src="/uploads/packages/'.$package->pic.'"  class="thumbnail" style="margin-bottom:0px !important; height:30px;" />';
                 return $pic;
             })            
            ->addColumn('status',function($package){
                return 'Activated';               
            })
            ->addColumn('actions',function($package) {
                $actions = '<a href='. route('packages.show', $package->id) .'><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="View Package"></i></a>
                            <a href='. route('packages.edit', $package->id) .'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Update Package"></i></a>';
                if ((Sentinel::getUser()->id != $package->id) && ($package->id != 1)) {
                    $actions .= '<a href='. route('packages.confirm-delete', $package->id) .' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete Package"></i></a>';
                }
                return $actions;
            })           
            ->rawColumns(['actions','pic'])
            ->make(true);
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        //updating the newsItem will cause an activity being logged
        return view('admin.package.create');
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        
        $data = new stdClass();
        
        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/packages/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }

        $package = Package::create([
            'name'      => $request->input('name'),
            'pic'       => $request->input('pic'),
            'stock'     => $request->input('stock'),
        ]);
        
        // Redirect to the home page with success menu
        return Redirect::route('package.index')->with('success', 'Completed successfully.');        
    }

    /**
     * User update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($package_id)
    {    
        $package = Package::find($package_id);

        //echo json_encode($user); return; 
        $data =[
            'package'    => $package,
        ];
        // Show the page
        return view('admin.package.edit')->with($data);
    }

    /**
     * User update form processing page.
     *
     * @param  User $package
     * @param UserRequest $package
     * @return Redirect
     */
    public function update($package_id, Request $request)
    {
        //echo json_encode($request->all()); return;
        $package = Package::find($package_id);               
        $package->update($request->except('pic_file'));
        // is new image for school uploaded?
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/packages/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            //delete old pic if exists
            if (File::exists($destinationPath . $package->pic)) {
                File::delete($destinationPath . $package->pic);
            }
            //save new file path into db
            $package->pic = $safeName;
        }             
        $package->save();
        return Redirect::route('package.edit', $package)->with('success', "Completed successfully.");
    }

    /**
     * Show a list of all the deleted users.
     *
     * @return View
     */
    public function getDeletedPackages()
    {
        // Grab deleted users
        $packages = Package::where('status','2')->get();
        
        // Show the page
        return view('admin.package.deleted_packages', compact('packages'));
    }

    
    /**
     * Delete the given user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {
        // Get user information
        $package = Package::find($id);
        // Delete Package
        $package->status = 2;
        $package->save();
        // Prepare the success message
        $success = 'Deleted successfully';
        // Redirect to the package management page
        return Redirect::route('packages.index')->with('success', $success);        
    }

    /**
     * Restore a deleted user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function getRestore($id)
    {
           // Get user information
           $package = Package::find($id);
           // Delete Package
           $package->status = 1;
           $package->save();
           // Prepare the success message
           $success = 'Deleted successfully';
           // Redirect to the package management page
           return Redirect::route('packages.index')->with('success', $success);        
    }

    /**
     * Display specified user profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        // Get the user information
        $package = Package::find($id);
        
        // Show the page
        $data =[
            'package'    => $package,            
       ];
        return view('admin.packages.show')->with($data);
    }   
}
