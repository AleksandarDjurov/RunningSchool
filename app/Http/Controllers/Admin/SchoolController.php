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
use App\Models\School;


class SchoolController extends JoshController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index()
    {
        $schools = \DB::table('schools as sc')                    
                    ->leftJoin('users as u', 'u.id', '=', 'sc.user_id')                                        
                    ->leftJoin('role_users as ru', 'ru.user_id', '=', 'u.id')
                    ->leftJoin('roles as r', 'r.id', '=', 'ru.role_id')                    
                    ->select(['sc.id', 'sc.logo_path', 'sc.name as school_name', 'u.first_name','u.last_name', 'u.email','u.created_at'])
                    ->where('r.id','=', 2)
                    ->where('u.deleted_at', NULL)
                    ->orderby('u.created_at', 'desc')
                    ->get();     
        // Show the page
        return view('admin.schools.index', compact('schools'));
    }
    
    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function data()
    {          
      
        $schools = \DB::table('schools as sc')                    
                    ->leftJoin('users as u', 'u.id', '=', 'sc.user_id')                                        
                    ->leftJoin('role_users as ru', 'ru.user_id', '=', 'u.id')
                    ->leftJoin('roles as r', 'r.id', '=', 'ru.role_id')                    
                    ->select(['sc.id', 'sc.logo_path', 'sc.name as school_name', 'u.first_name','u.last_name', 'u.email','u.created_at'])
                    ->where('r.id','=', 2)
                    ->where('u.deleted_at', NULL)
                    ->orderby('u.created_at', 'desc')
                    ->get();        
      
        return DataTables::of($schools)
            // ->editColumn('created_at',function($school) {
            //     return $school->created_at->diffForHumans();
            // })
             ->editColumn('logo_path',function($school) {
                $logo_path = '<img src="/uploads/schools/'.$school->logo_path.'"  class="thumbnail" style="margin-bottom:0px !important; height:30px;" />';
                 return $logo_path;
             })            
            ->addColumn('status',function($school){
                return 'Activated';               
            })
            ->addColumn('actions',function($school) {
                $actions = '<a href='. route('schools.show', $school->id) .'><i class="livicon" data-name="user-add" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Technical Person"></i></a>
                            <a href='. route('schools.show', $school->id) .'><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="View School"></i></a>
                            <a href='. route('schools.edit', $school->id) .'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="Update School"></i></a>';
                if ((Sentinel::getUser()->id != $school->id) && ($school->id != 1)) {
                    $actions .= '<a href='. route('schools.confirm-delete', $school->id) .' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete school"></i></a>';
                }
                return $actions;
            })           
            ->rawColumns(['actions','logo_path'])
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
        $groups = Sentinel::getRoleRepository()->where('id','=' ,2)->get();
        
        $states = Util::State_list();                  
        $countries = $this->countries;
              
        // Show the page
        //updating the newsItem will cause an activity being logged
        return view('admin.schools.create', compact('groups', 'countries', 'states'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(UserRequest $request)
    {
        
        $data = new stdClass();
        
        //upload image for user profile
        if ($file = $request->file('logo_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/schools/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['logo'] = $safeName;
        }

        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }

        $school_state = $request->get('school_state');
        $request['school_state'] = Util::getStateName($school_state);
        $school_region = $request->get('school_region');
        $request['school_region'] = Util::getRegionName($school_region);
        $school_province = $request->get('school_province');
        $request['school_province'] = Util::getProvinceName($school_province);

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
            $user = Sentinel::register($request->
                                    except('_token', 'password_confirm', 'group', 'activate', 'pic_file', 'logo', 'logo_file',
                                                'school_name','reference_asd','company_code', 'school_state', 'school_province',
                                                'school_region','school_city', 'school_address', 'postal_code'), $activate);
           
            //add user to 'User' group
            $role = Sentinel::findRoleById($request->get('group'));
            if ($role) {
                $role->users()->attach($user);
            }
            
            //register Sschool
            if($user){
                $school = School::create([        
                    'name'          => $request->input('school_name'),
                    'logo_path'     => $request->input('logo'),
                    'reference_asd' => $request->input('reference_asd'),
                    'company_code'  => $request->input('company_code'),
                    'state'         => $request->input('school_state'),
                    'region'        => $request->input('school_region'),
                    'province'      => $request->input('school_province'),
                    'city'          => $request->input('school_city'),
                    'address'       => $request->input('school_address'),
                    'postal_code'   => $request->input('postal_code'),
                    'user_id'       => $user->id                    
                    ]);

                //add school_user group
                $shcool_user_check = \DB::table('school_user')
                ->where('school_id', $school->id)
                ->where('role_id', 2)
                ->where('user_id', $user->id)
                ->first();

                if(!$shcool_user_check)                        
                    $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?,?)', [$school->id, $user->id, 2]);

                      
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
            return Redirect::route('schools.index')->with('success', trans('schools/message.success.create'));

        } catch (LoginRequiredException $e) {
            $error = trans('admin/schools/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = trans('admin/schools/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = trans('admin/schools/message.user_exists');
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
    public function edit($school_id)
    {    

        $school = School::find($school_id);
        $user_id = $school->user_id;       
        $user = Sentinel::findUserById($user_id);          
        // Get this user groups
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();
        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->where('id',2)->get();

        $status = Activation::completed($user);
        
        $states = Util::State_list();                         
        $countries = $this->countries;
      
       $school->state_id = Util::getStateId($school->state);        
       $school->region_id = Util::getRegionId($school->region); 
       $school->province_id = Util::getProvinceId($school->province);
       
       $user->state_id = Util::getStateId($user->state);        
       $user->region_id = Util::getRegionId($user->region); 
       $user->province_id = Util::getProvinceId($user->province);
       //echo json_encode($user); return; 
       $data =[
            'school'    => $school,
            'user'      => $user,           
            'states'    => $states,
            'roles'     => $roles,
            'userRoles' => $userRoles,
            'countries' => $countries,
            'status'    => $status
            
       ];
        // Show the page
        return view('admin.schools.edit')->with($data);
    }

    /**
     * User update form processing page.
     *
     * @param  User $school
     * @param UserRequest $request
     * @return Redirect
     */
    public function update($school_id, UserRequest $request)
    {
        //echo json_encode($request->all()); return;
        $school = School::find($school_id);        
        $user_id = $school->user_id;
        $user = Sentinel::findUserById($user_id);          
        $data = new stdClass();

        try {
            $user->update($request->except('pic_file','password','password_confirm','groups','activate',
                                            'logo', 'logo_file','school_name','reference_asd','company_code', 'school_state', 'school_province',
                                            'school_region','school_city', 'school_address', 'postal_code'));

            if ($password = $request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            
            // is new image for school uploaded?
            if ($file = $request->file('logo_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/schools/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists($destinationPath . $school->logo_path)) {
                    File::delete($destinationPath . $school->logo_path);
                }
                //save new file path into db
                $school->logo_path = $safeName;
            } 
            
                $school->name   = $request->input('school_name');                
                $school->reference_asd  =  $request->input('reference_asd');
                $school->company_code   = $request->input('company_code');                                
                $school->address        = $request->input('school_address');
                $school->postal_code    = $request->input('postal_code');
                
            // is new image for user uploaded?
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
            //state, province, city, ..
            $school_state       = $request->get('school_state');
            $school->state      = Util::getStateName($school_state);
            $school_region      = $request->get('school_region');           
            $school->region     = Util::getRegionName($school_region);
            $school_province    = $request->get('school_province');
            $school->province   = Util::getProvinceName($school_province);
            
            $state              = $request->get('state');
            $user->state        = Util::getStateName($state);
            $region             = $request->get('region');           
            $user->region       = Util::getRegionName($region);
            $province           = $request->get('province');
            $user->province     = Util::getProvinceName($province); 

            //save school
            $school->save();

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

             //add school_user group
             $shcool_user_check = \DB::table('school_user')
             ->where('school_id', $school->id)
             ->where('role_id', 2)
             ->where('user_id', $user->id)
             ->first();
             if(!$shcool_user_check)                        
                $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?,?)', [$school_id, $user->id, 2]);


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
                return Redirect::route('schools.edit', $school)->with('success', $success);
            }

            // Prepare the error message
            $error = trans('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('schools.index')->with('error', $error);
        }

        // Redirect to the user page
        return Redirect::route('schools.edit', $school)->withInput()->with('error', $error);
    }

    /**
     * Show a list of all the deleted users.
     *
     * @return View
     */
    public function getDeletedSchools()
    {
        // Grab deleted users
        $schools = \DB::table('schools as sc')
            ->leftJoin('users as u', 'u.id', '=', 'sc.user_id')
            ->leftJoin('role_users as ru', 'ru.user_id', '=', 'u.id')
            ->leftJoin('roles as r', 'r.id', '=', 'ru.role_id')                    
            ->select([ 'sc.name as schhol_name','u.id', 'u.first_name', 'u.last_name', 'u.email','r.name as role_name', 'u.created_at'])
            ->where('r.id','=', 2)
            ->where('sc.state', '=', 2)
            ->orderby('u.created_at', 'desc')
            ->get();
        
        // Show the page
        return view('admin/schools/deleted_schools', compact('schools'));
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
        $confirm_route = route('schools.delete', ['id' => $user->id]);
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
                return Redirect::route('schools.index')->with('error', $error);
            }
            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            User::destroy($id);
            // Prepare the success message
            $success = trans('users/message.success.delete');

            // Redirect to the user management page
            return Redirect::route('schools.index')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('schools.index')->with('error', $error);
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
            return Redirect::route('deleted_schools')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('deleted_schools')->with('error', $error);
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
            $school = School::find($id);
            $user_id = $school->user_id;
            $user = Sentinel::findUserById($user_id);
            // Get this user groups
            $userRoles = $user->getRoles()->pluck('name', 'id')->all();
            // Get a list of all the available groups
            $roles = Sentinel::getRoleRepository()->where('id',2)->get();
            $status = Activation::completed($user);
            //get country name
            if ($user->country) {
                //$user->country = $this->countries[$user->country];
                $user->country = 'Italy';
            }
            $tech_user = false;
            $school_id  = $school->id;
            $user_id    = $user->id;
            $role_id    = 3; // technical person
            $tech  = \DB::table('school_user')
                        ->where('school_id', $school_id)
                        ->where('role_id', $role_id)                        
                        ->first();
            if($tech) $tech_user = true;             
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            // Redirect to the user management page
            return Redirect::route('users.index')->with('error', $error);
        }
        $states = Util::State_list();                  
        $countries = $this->countries;
        $groups = Sentinel::getRoleRepository()->where('id','=' ,3)->get();
        
        
        //get technical person
        $user_person = array();        
        if($tech_user) {
            $tech_user_id   = $tech->user_id;
            $user_person    = Sentinel::findUserById($tech_user_id);
            $user_person->state_id = Util::getStateId($user->state);        
            $user_person->region_id = Util::getRegionId($user->region); 
            $user_person->province_id = Util::getProvinceId($user->province);
        }
        // Show the page
        $data =[
            'school'    => $school,
            'user'      => $user,           
            'states'    => $states,
            'roles'     => $roles,
            'userRoles' => $userRoles,
            'countries' => $countries,
            'status'    => $status,
            'groups'    => $groups,
            'tech_user' => $tech_user,
            'user_person'=> $user_person,            
       ];
        return view('admin.schools.show')->with($data);
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

    public function addTechnical(Request $request) {
        $data = new stdClass();
        
        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = $safeName;
        }
        $school_id  = $request->get('school_id');
        $role_id    = '3'; 
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
            $user = Sentinel::register($request->
                                    except('_token', 'password_confirm', 'group', 'activate', 'pic_file','school_id'), $activate);
           
            //add user to 'User' group
            $role = Sentinel::findRoleById($request->get('group'));
            if ($role) {
                $role->users()->attach($user);
            }
            //add school_user group
            $shcool_user_check = \DB::table('school_user')
                                    ->where('school_id', $school_id)
                                    ->where('role_id', $role_id)
                                    ->where('user_id', $user->id)
                                    ->first();
            if(!$shcool_user_check)                        
                $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?,?)', [$school_id, $user->id, $role_id]);
           
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
            return Redirect::route('schools.show')->with('success', trans('schools/message.success.create'));

        } catch (LoginRequiredException $e) {
            $error = trans('admin/schools/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = trans('admin/schools/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = trans('admin/schools/message.user_exists');
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }

    public function updateTechnical($user_id, Request $request) {
        
         //echo json_encode($request->all()); return;         
         $user = Sentinel::findUserById($user_id);          
         $data = new stdClass();
 
         try {
             $user->update($request->except('pic_file','school_id','password','password_confirm','groups','activate'));
 
             if ($password = $request->has('password')) {
                 $user->password = Hash::make($request->password);
             }   
             // is new image for user uploaded?
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
            
             $school_id  = $request->get('school_id');
             $school = School::find($school_id);
             // Was the user updated?
             if ($user->save()) {
                 // Prepare the success message
                 $success = trans('users/message.success.update');
 //                 Redirect to the user page
                 return Redirect::route('schools.show', $school)->with('success', $success);
             }
 
             // Prepare the error message
             $error = trans('users/message.error.update');
         } catch (UserNotFoundException $e) {
             // Prepare the error message
             $error = trans('users/message.user_not_found', compact('id'));
 
             // Redirect to the user management page
             return Redirect::route('schools.index')->with('error', $error);
         }
 
         // Redirect to the user page
         return Redirect::route('schools.show', $school)->withInput()->with('error', $error);
        
    }
   
}
