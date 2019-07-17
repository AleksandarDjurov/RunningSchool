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
use Psy\Util\Json;

class ApiPersonalTrainerController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->get('status');
        $school_id = $request->get('school_id');

        $ret = array();
        // if(!$school_id) {
        //     $ret['code']    = '401';
        //     $ret['msg']     = 'Please select school name.';
        //     return Response::json($ret);
        // }

        if (!$school_id)
        {
            $trainers = \DB::table('users as u')
                ->leftJoin('school_user as sc', 'u.id', '=', 'sc.user_id')
                ->leftJoin('schools as sch', 'sc.school_id', '=', 'sch.id')
                ->leftJoin('role_users as rou', 'rou.user_id', '=', 'u.id')
                ->leftJoin('roles as ros', 'rou.role_id', '=', 'ros.id')
                ->select(['u.*','sc.school_id','sch.name as school_name','rou.role_id as role_id','ros.name as role_name'])
                ->where(function($query){
                    $query->where('rou.role_id', '=', 3)
                        ->orWhere('rou.role_id', '=', 4);
                });            
        }
        else
        {
            $trainers = \DB::table('users as u')
                ->leftJoin('school_user as sc', 'u.id', '=', 'sc.user_id')
                ->leftJoin('schools as sch', 'sc.school_id', '=', 'sch.id')
                ->leftJoin('role_users as rou', 'rou.user_id', '=', 'u.id')
                ->leftJoin('roles as ros', 'rou.role_id', '=', 'ros.id')
                ->select(['u.*','sc.school_id','sch.name as school_name','rou.role_id as role_id','ros.name as role_name'])
                ->where('sc.school_id', '=', $school_id)
                ->where(function($query){
                    $query->where('rou.role_id', '=', 3)
                        ->orWhere('rou.role_id', '=', 4);
                });            
        }

        if($status != '5')
            $trainers = $trainers->where('u.status', $status);

        $trainers   = $trainers->orderby('u.created_at', 'desc')
            ->get();

        return Response::json($trainers);
    }

    //create psersonnal trainer
    public function createPersonalTrainer(Request $request) {
        $data = new stdClass();
        $ret = array();

        //$school_id  = $request->get('school_id');
        //$school     = School::find($school_id);;
        // if(!$school) {
        //     $ret['code']    = '401';
        //     $ret['msg']     = 'Please select school name.';
        //     return Response::json($ret);
        // }
        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/users/'.$safeName;
        }

        $role_id    = '4'; //personal trainer
        $state = $request->get('state');
        if($state)
            $request['state'] = Util::getStateName($state);
        $region = $request->get('region');
        if($region)
            $request['region'] = Util::getRegionName($region);
        $province = $request->get('province');
        if($province)
            $request['province'] = Util::getProvinceName($province);

        //check whether use should be activated by default or not
        $activate = $request->get('activate') ? true : false;
        $request['password'] = '123456'; //defualt password
        DB::beginTransaction();
        try {
            if(!$request->get('email')) {
                $ret['code']    = '401';
                $ret['msg']     = 'All items can not register. Please check email.';
                return Response::json($ret);
            }

            $email_confirm = DB::table('users')->where('email', $request->get('email'))-> first();
            if($email_confirm) {
                $ret['code']    = '402';
                $ret['msg']     = 'Email registered already. Please check email.';
                return Response::json($ret);
            }

            // Register the user
            $req = $request->except( 'password_confirm', 'group', 'activate', 'pic_file','school_id', 'user_upload', 'user_download');
            $user = Sentinel::register( $req , $activate);

            //add user to 'User' group
            $role = Sentinel::findRoleById('4');//$request->get('group') for technical person
            if ($role) {
                $role->users()->attach($user);
            }
            //add school_user group
//            $shcool_user_check = \DB::table('school_user')
//                ->where('school_id', $school_id)
//                ->where('role_id', $role_id)
//                ->where('user_id', $user->id)
//                ->first();
//            if(!$shcool_user_check)
//                $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?, ?)', [$school_id, $user->id, $role_id]);

            //upload and sownload
            if($user) {
                $user_uploads  = $request->file('user_upload');
                if($user_uploads) {
                    foreach ($user_uploads as $file) {
                        if ($file) {
                            $type       = $file->extension()?: 'png';
                            $size       = $file->getSize();
                            $destinationPath = public_path() . '/uploads/users/';
                            $safeName = str_random(10) . '.' . $type;
                            $file->move($destinationPath, $safeName);
                            $path = '/uploads/users/'.$safeName;
                            $user_upload = \DB::insert('insert into user_upload (user_id, type, url, size)
                                                                    values (?,?,?,?)', [$user->id, $type, $path, $size]);
                        }
                    }
                }

                $user_downloads  = $request->file('user_download');
                if($user_downloads) {
                    foreach ($user_downloads as $file) {
                        if ($file) {
                            $type       = $file->extension()?: 'png';
                            $size       = $file->getSize();
                            $destinationPath = public_path() . '/uploads/users/';
                            $safeName = str_random(10) . '.' . $type;
                            $file->move($destinationPath, $safeName);
                            $path = '/uploads/users/'.$safeName;
                            $user_download = \DB::insert('insert into user_download (user_id, type, url, size)
                                                                    values (?,?,?,?)', [$user->id, $type, $path, $size]);
                        }
                    }
                }
            }else {
                DB::rollBack();
                $ret['code']    = '403';
                $ret['msg']     = 'Can not register. Please check all items.';
                return Response::json($ret);
            }
            //check for activation and send activation mail if not activated by default
            if (!$request->get('activate')) {
                // Data to be used on the email view
                $data->user_name = $user->first_name .' '. $user->last_name;
                $data->activationUrl = URL::route('activate', [$user->id, Activation::create($user)->code]);

                // Send the activation code through email
                //Mail::to($user->email)
                //    ->send(new Restore($data));
            }

            // Redirect to the home page with success menu
            DB::commit();
        } catch (LoginRequiredException $e) {
            DB::rollBack();
            $ret['code']    = '403';
            $ret['msg']     = 'Can not register. Please check all items.';
            return Response::json($ret);
        } catch (PasswordRequiredException $e) {
            DB::rollBack();
            $ret['code']    = '403';
            $ret['msg']     = 'Can not register. Please check all items.';
            return Response::json($ret);
        } catch (UserExistsException $e) {
            DB::rollBack();
            $ret['code']    = '403';
            $ret['msg']     = 'Can not register. Please check all items.';
            return Response::json($ret);
        }

        // Redirect to the user creation page
        $ret['code']    = '200';
        $ret['msg']     = 'Completed Successfully.';
        return Response::json($ret);
    }
    //
    public function updatePersonalTrainer($user_id, Request $request) {
        $ret = array();
        //echo json_encode($request->all()); return;
        // $user = Sentinel::findUserById($user_id);

        $current_status = $request->input('status', '0');
        $user = \DB::table('users')->where('id', $user_id)->update(['status'=>$current_status]);
        $user = Sentinel::findUserById($user_id);

        $data = new stdClass();
        DB::beginTransaction();
        try {
            // $user->update($request->except('user_id','token','pic_file','school_id','password','password_confirm','groups',
            //     'activate','user_upload', 'user_download'));

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
                if (File::exists(public_path() . $user->pic)) {
                    File::delete(public_path() . $user->pic);
                }
                //save new file path into db
                $user->pic = '/uploads/users/'.$safeName;
            }

            $state              = $request->get('state');
            if($state)
                $user->state        = Util::getStateName($state);
            $region             = $request->get('region');
            if($region)
                $user->region       = Util::getRegionName($region);
            $province           = $request->get('province');
            if($province)
                $user->province     = Util::getProvinceName($province);

            //save record
            $user->save();
            if($user) {
                $user_uploads  = $request->file('user_upload');
                if($user_uploads) {
                    foreach ($user_uploads as $file) {
                        if ($file) {
                            $type       = $file->extension()?: 'png';
                            $size       = $file->getSize();
                            $destinationPath = public_path() . '/uploads/users/';
                            $safeName = str_random(10) . '.' . $type;
                            $file->move($destinationPath, $safeName);
                            $path = '/uploads/users/'.$safeName;
                            $user_upload = \DB::insert('insert into user_upload (user_id, type, url, size)
                                                                    values (?,?,?,?)', [$user->id, $type, $path, $size]);
                        }
                    }
                }

                $user_downloads  = $request->file('user_download');
                if($user_downloads) {
                    foreach ($user_downloads as $file) {
                        if ($file) {
                            $type       = $file->extension()?: 'png';
                            $size       = $file->getSize();
                            $destinationPath = public_path() . '/uploads/users/';
                            $safeName = str_random(10) . '.' . $type;
                            $file->move($destinationPath, $safeName);
                            $path = '/uploads/users/'.$safeName;
                            $user_download = \DB::insert('insert into user_download (user_id, type, url, size)
                                                                    values (?,?,?,?)', [$user->id, $type, $path, $size]);
                        }
                    }
                }
            }
            // Get the current user groups
            $userRoles = $user->roles()->pluck('id')->all();

            // Get the selected groups

            $selectedRoles = ['4']; // $request->get('groups'); 3 is technical manager

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            if($userRoles) {
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
//                    Mail::to($user->email)
//                        ->send(new Restore($data));

                }
            }

            $school_id  = $request->get('school_id');
            if($school_id) {
                //$school = School::find($school_id);
                $role_id = 4 ;
                $shcool_user_check = \DB::table('school_user')
                    ->where('school_id', $school_id)
                    ->where('role_id', $role_id)
                    ->where('user_id', $user->id)
                    ->first();
                if(!$shcool_user_check)
                    $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?, ?)', [$school_id, $user->id, $role_id]);
            }
            // Was the user updated?
            if ($user->save()) {
                DB::commit();
                $ret['code']    = 200;
                $ret['msg']     = "Completed Successfully";
                return Response::json($ret);
            }
            DB::rollBack();
            // Prepare the error message
            $error = 'Can not completed. Please check all items.';
        } catch (UserNotFoundException $e) {
            DB::rollBack();
            // Prepare the error message
            $error = 'Can not found user' ;
        } catch(QueryException $e) {
            $error = 'SqlException error.' ;
        }

        DB::rollBack();
        $ret['code']    = 401;
        $ret['msg']     = $error;
        return Response::json($ret);
    }

    public function showPersonalTrainer($id, Request $request) {

        $personal = Sentinel::findUserById($id);
        if($personal) {
            $personal->state_id  = Util::getStateId($personal->state);
            $personal->region_id = Util::getRegionId($personal->region);
            $personal->province_id = Util::getProvinceId($personal->province);
            $personal->country = 'Italy';
            $user_upload  = DB::table('user_upload')->where('user_id', $id)->get();
            $user_download = DB::table('user_download')->where('user_id', $id)->get();

            $personal->upload     = $user_upload;
            $personal->download   = $user_download;
        }

        // Show the page
        $ret = array();
        $ret['personal']= $personal;
        return Response::json($ret);
    }
}
