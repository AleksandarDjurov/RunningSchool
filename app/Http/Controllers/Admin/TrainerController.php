<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\School;
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

class TrainerController extends JoshController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index()
    {
      
        // Show the page
        return view('admin.trainer.index', compact('trainer'));
    }
    
    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function data()
    {           
        $trainers = \DB::table('users as u')
                    ->leftJoin('role_users as ru', 'ru.user_id', '=', 'u.id')
                    ->leftJoin('roles as r', 'r.id', '=', 'ru.role_id')                    
                    ->select(['u.id', 'u.first_name', 'u.last_name', 'u.email','r.name as role_name', 'r.id as role_id', 'u.created_at', 'u.pic'])                    
                    ->where(function ($query){
                        $query->where('r.id','=', '3')
                            ->orWhere('r.id','=','4');
                    })
                    ->where('u.deleted_at', NULL)
                    ->orderby('u.created_at', 'desc')
                    ->get();        
        

        return DataTables::of($trainers)
            // ->editColumn('created_at',function(User $club) {
            //     return $club->created_at->diffForHumans();
            // })
            ->editColumn('first_name',function($trainer) {                
                $first_name = '<div><img class="" src="'.URL::to('/').'/uploads/users/'.$trainer->pic.'" style="border-radius:50%;height:25px;padding-right:5px;"  />'.$trainer->first_name.' </div>';
                 return $first_name ;
             })
            ->editColumn('role_name',function($trainer) {
                
                if($trainer->role_id == '3')
                    $role_name ='<span style="background-color:#b3f5e3">'.$trainer->role_name.'</span>'; 
                
                if($trainer->role_id == '4')
                    $role_name ='<span>'.$trainer->role_name.'</span>'; 

                 return $role_name ;
             })
            ->addColumn('status',function($trainer){
                return 'Activated';               
            })
            ->addColumn('actions',function($trainer) {
                $actions = '<a href='. route('trainer.show', $trainer->id) .'><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view trainer"></i></a>
                            <a href='. route('trainer.edit', $trainer->id) .'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update trainer"></i></a>';
                // if ((Sentinel::getUser()->id != $trainer->id) && ($trainer->id != 1)) {
                //     $actions .= '<a href='. route('trainer.confirm-delete', $trainer->id) .' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete trainer"></i></a>';
                // }
                return $actions;
            })
            ->rawColumns(['actions','role_name', 'first_name'])
            ->make(true);
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function create()
    {

        // Get all the available groups
        $groups = Sentinel::getRoleRepository()->where('id','=' ,4)->get();

        $states = Util::State_list();                  
        $countries = $this->countries;
        $schools     = School::all();
        // Show the page
        //updating the newsItem will cause an activity being logged
        return view('admin.trainer.create', compact('groups', 'countries', 'states', 'schools'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(UserRequest $request)
    {
        // $all = $request->all();
        // echo json_encode($all); return;
        $data = new stdClass();
        //upload image
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }
        
        $state = $request->get('state');
        $request['state'] = Util::getStateName($state);
        $region = $request->get('region');
        $request['region'] = Util::getRegionName($region);
        $province = $request->get('province');
        $request['province'] = Util::getProvinceName($province);


        //check whether use should be activated by default or not
        $activate = $request->get('activate') ? true : false;

        try {
            // Register the user
            $user = Sentinel::register($request->except('_token', 'password_confirm', 'group', 'activate', 'pic_file', 'school_id'), $activate);

            //add user to 'User' group
            $role = Sentinel::findRoleById($request->get('group'));
            if ($role) {
                $role->users()->attach($user);
            }
            if($user) {
                $school_id = $request->get('school_id');
                 //add school_user group
                 $shcool_user_check = \DB::table('school_user')
                    ->where('school_id', $school_id)
                    ->where('role_id', 4)
                    ->where('user_id', $user->id)
                    ->first();
                if(!$shcool_user_check)                        
                    $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?, ?)', [$school_id, $user->id, 4]);
            }

            //check for activation and send activation mail if not activated by default
            if (!$request->get('activate')) {
                // Data to be used on the email view
                $data->user_name = $user->first_name .' '. $user->last_name;
                $data->activationUrl = URL::route('activate', [$user->id, Activation::create($user)->code]);

                // Send the activation code through email
                Mail::to($user->email)
                    ->send(new Restore($data));
            }

            // Redirect to the home page with success menu
            return Redirect::route('trainer.index')->with('success', trans('users/message.success.create'));

        } catch (LoginRequiredException $e) {
            $error = trans('admin/trainer/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = trans('admin/trainer/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = trans('admin/trainer/message.user_exists');
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }

    /**
     * User update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($user_id)
    {   
        $schools = School::all();        
        $user = Sentinel::findUserById($user_id);   
        $role = \DB::table('role_users')
                        ->where('user_id', $user->id)->first();
        $user->role_id = $role->role_id;

        // Get this user groups
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();
        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->where('id',4)->get();

        $status = Activation::completed($user);
        $states = Util::State_list();       
        $countries = $this->countries;

        $user->state_id = Util::getStateId($user->state);        
        $user->region_id = Util::getRegionId($user->region); 
        $user->province_id = Util::getProvinceId($user->province);
        $school = \DB::table('school_user')
                            ->where('user_id', $user->id )
                            ->where('role_id', $user->role_id)
                            ->first();
        if($school)
            $user->school_id = $school->school_id;
        else 
            $user->school_id = 0;
        
        $data =[
            'schools'    => $schools,
            'user'       => $user,   
            'states'    => $states,        
            'roles'      => $roles,
            'userRoles'  => $roles,
            'userRoles'  => $userRoles,
            'countries'  => $countries,
            'status'     => $status            
       ];
        // Show the page
        return view('admin.trainer.edit')->with($data);       
    }

    /**
     * User update form processing page.
     *
     * @param  User $club
     * @param UserRequest $request
     * @return Redirect
     */
    public function update($user_id, UserRequest $request)
    {
        $user = Sentinel::findUserById($user_id);          
        $data = new stdClass();

        try {
            $user->update($request->except('pic_file','password','password_confirm','groups','activate', 'school_id'));

            if ($password = $request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            // is new image uploaded?
            if ($file = $request->file('pic_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/users/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists($destinationPath . $user->pic)) {
                    File::delete($destinationPath . $user->pic);
                }
                //save new file path into db
                $user->pic = $safeName;
            }

            $state              = $request->get('state');
            $user->state        = Util::getStateName($state);
            $region             = $request->get('region');           
            $user->region       = Util::getRegionName($region);
            $province           = $request->get('province');
            $user->province     = Util::getProvinceName($province); 


            //save record
            $user->save();

            // Get the current user groups
            $userRoles = $user->roles()->pluck('id')->all();

            // Get the selected groups

            $selectedRoles = $request->get('groups');

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            $rolesToAdd = array_diff($selectedRoles, $userRoles);
            $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups

            foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($user);
            }

            // Remove the user from groups
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->detach($user);
            }

            $school_id = $request->get('school_id');
            //add school_user group
            $shcool_user_check = \DB::table('school_user')
               ->where('school_id', $school_id)
               ->where('role_id', 4)
               ->where('user_id', $user->id)
               ->first();
           if(!$shcool_user_check)                        
               $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?, ?)', [$school_id, $user->id, 4]);

            // Activate / De-activate user

            $status = $activation = Activation::completed($user);

            if ($request->get('activate') != $status) {
                if ($request->get('activate')) {
                    $activation = Activation::exists($user);
                    if ($activation) {
                        Activation::complete($user, $activation->code);
                    }
                } else {
                    //remove existing activation record
                    Activation::remove($user);
                    //add new record
                    Activation::create($user);
                    //send activation mail
                    $data->user_name =$user->first_name .' '. $user->last_name;
                    $data->activationUrl = URL::route('activate', [$user->id, Activation::exists($user)->code]);
                    // Send the activation code through email
                    Mail::to($user->email)
                        ->send(new Restore($data));

                }
            }

            // Was the user updated?
            if ($user->save()) {
                // Prepare the success message
                $success = trans('users/message.success.update');

//                 Redirect to the user page
                return Redirect::route('trainer.edit', $user)->with('success', $success);
            }

            // Prepare the error message
            $error = trans('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('trainer.index')->with('error', $error);
        }

        // Redirect to the user page
        return Redirect::route('trainer.edit', $user)->withInput()->with('error', $error);
    }

    /**
     * Show a list of all the deleted users.
     *
     * @return View
     */
    public function getDeletedTrainer()
    {
        // Grab deleted users
        //$users = User::onlyTrashed()->get();
        $users = \DB::table('users as u')
        ->leftJoin('role_users as ru', 'ru.user_id', '=', 'u.id')
        ->leftJoin('roles as r', 'r.id', '=', 'ru.role_id')                    
        ->select(['u.id', 'u.first_name', 'u.last_name', 'u.email','r.name as role_name', 'u.created_at'])
        ->where('r.id','=', 3)
        ->where('u.deleted_at', '!=', NULL)
        ->orderby('u.created_at', 'desc')
        ->get();  
        // Show the page
        return view('admin/trainer/deleted_clubs', compact('users'));
    }


    /**
     * Delete Confirm
     *
     * @param   int $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $model = 'users';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');

                return view('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            return view('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        $confirm_route = route('trainer.delete', ['id' => $user->id]);
        return view('layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    /**
     * Delete the given user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {

        try {
            // Get user information
            $user = Sentinel::findById($id);
            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');
                // Redirect to the user management page
                return Redirect::route('trainer.index')->with('error', $error);
            }
            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            User::destroy($id);
            // Prepare the success message
            $success = trans('users/message.success.delete');

            // Redirect to the user management page
            return Redirect::route('trainer.index')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('trainer.index')->with('error', $error);
        }
    }

    /**
     * Restore a deleted user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function getRestore($id)
    {
        $data = new stdClass();
        try {
            // Get user information
            $user = User::withTrashed()->find($id);
            // Restore the user
            $user->restore();
            // create activation record for user and send mail with activation link
            $data->user_name = $user->first_name .' '. $user->last_name;
            $data->activationUrl = URL::route('activate', [$user->id, Activation::create($user)->code]);
            // Send the activation code through email
            Mail::to($user->email)
                ->send(new Restore($data));
            // Prepare the success message
            $success = trans('users/message.success.restored');
            // Redirect to the user management page
            return Redirect::route('deleted_clubs')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('deleted_trainer')->with('error', $error);
        }
    }

    /**
     * Display specified user profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            // Get the user information
            $user = Sentinel::findUserById($id);
            //get country name
            if ($user->country) {
                $user->country = $this->countries[$user->country];
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            // Redirect to the user management page
            return Redirect::route('trainer.index')->with('error', $error);
        }
        // Show the page
        return view('admin.trainer.show', compact('user'));

    }

    public function passwordreset($id, Request $request)
    {
        $user = Sentinel::findUserById($id);
        $password = $request->get('password');
        $user->password = Hash::make($password);
        $user->save();
    }

    public function lockscreen($id){
        $user = Sentinel::findUserById($id);
        return view('lockscreen',compact('user'));
    }

    public function postLockscreen(Request $request){
        $password = Sentinel::getUser()->password;
        if(Hash::check($request->password,$password)){
            return 'success';
        } else{
            return 'error';
        }
    }
}
