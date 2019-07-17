<?php namespace App\Http\Controllers\Api\V1;


use App\Http\Datautil\Util;
use App\Http\Requests\UserRequest;
use App\Mail\Restore;
use App\Models\School;
use App\Models\User;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
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


class ApiAthleteController extends Controller
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

        $athletes = \DB::table('users as u')
            ->leftJoin('school_user as sc', 'u.id', '=', 'sc.user_id')
            ->leftJoin('schools as sch', 'sc.school_id', '=', 'sch.id')
            ->leftJoin('role_users as rou', 'rou.user_id', '=', 'u.id')
            ->leftJoin('roles as ros', 'rou.role_id', '=', 'ros.id')
            ->select(['u.*','sc.school_id','sch.name as school_name','rou.role_id as role_id','ros.name as role_name'])
            ->where('rou.role_id', '=', 5);
        if($status != '5')
            $athletes = $athletes->where('u.status', $status);

        $athletes   = $athletes->orderby('u.created_at', 'desc')
            ->get();

        return Response::json($athletes);
    }

    //create athlete
    public function createAthlete(Request $request) {
        $data = new stdClass();
        $ret = array();

        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/users/'.$safeName;
        }

        $role_id    = '5'; //personal trainer
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

        $email    = $request->get('email');
        $password = $request->get('password');
        if(!$password) {
            $password = '123456';
        }
        $request['password'] = $password; //defualt password
        $verify_code = base64_encode($password.":".$email);
        $request['verify_code'] = $verify_code;

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
            $role = Sentinel::findRoleById('5');//$request->get('group') for technical person
            if ($role) {
                $role->users()->attach($user);
            }

            //upload and download
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

        //send
        $data = array();
        $customer = \DB::table('mail_templates')->where('mailname', 'to_athlete_verify')->first();
        $domain = \Request::root();
        $verify = $password.":".$email;
        $verify = $domain.'/auth/verify/'.base64_encode($verify);

        if(!empty($customer)){
            $subject     = $customer->subject;
            $content     = $customer->content;
            $fromname    = $customer->sender;
            $user_name = $user->last_name.' '.$user->first_name;
            $content = str_replace('{user_name}', $user_name, $content);
            $content = str_replace('{verify}', $verify, $content);
            $content = str_replace('{domain}', $domain, $content);
            $mail_addresses = [
                $user->email, // guest email
            ];

            foreach ($mail_addresses as $address){
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
                $data[] = $data1;
            }
        }

        $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));

        if($user) {
            try {
                $ch = array();
                $mh = curl_multi_init();
                $ch[0] = curl_init();

                $base_url = url('/');
                curl_setopt($ch[0], CURLOPT_URL, $base_url."/mail/school/schoolmail.php");
                curl_setopt($ch[0], CURLOPT_HEADER, 0);
                curl_setopt($ch[0], CURLOPT_POST, true);
                curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
//               curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
//               curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                curl_setopt($ch[0], CURLOPT_POST, true);
                curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
                curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
                curl_multi_add_handle($mh, $ch[0]);
                $active = null;
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);

                while ($active && $mrc == CURLM_OK) {
                    // add this line
                    while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                    if (curl_multi_select($mh) != -1) {
                        do {
                            $mrc = curl_multi_exec($mh, $active);
                            if ($mrc == CURLM_OK) {
                            }
                        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                    }
                }
                //close the handles
                curl_multi_remove_handle($mh, $ch[0]);
                curl_multi_close($mh);
            } catch (Exception $e) {
            }
        }

        // Redirect to the user creation page
        $ret['code']    = '200';
        $ret['msg']     = 'Completed Successfully.';
        return Response::json($ret);
    }
    //
    public function updateAthlete($user_id, Request $request) {
        $ret = array();
        //echo json_encode($request->all()); return;
        $user = Sentinel::findUserById($user_id);
        $data = new stdClass();
        DB::beginTransaction();
        try {
            $user->update($request->except('user_id','token','pic_file','email','school_id','password','password_confirm','groups',
                'activate','user_upload', 'user_download'));

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

            $selectedRoles = ['5']; // $request->get('groups'); 3 is technical manager

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
                $role_id = 5 ;
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

    public function showAthlete($id, Request $request) {

        $athlete = Sentinel::findUserById($id);
        if($athlete) {
            $athlete->state_id  = Util::getStateId($athlete->state);
            $athlete->region_id = Util::getRegionId($athlete->region);
            $athlete->province_id = Util::getProvinceId($athlete->province);
            $athlete->country = 'Italy';
            $user_upload  = DB::table('user_upload')->where('user_id', $id)->get();
            $user_download = DB::table('user_download')->where('user_id', $id)->get();

            $athlete->upload     = $user_upload;
            $athlete->download   = $user_download;
        }

        // Show the page
        $ret = array();
        $ret['personal']= $athlete;
        return Response::json($ret);
    }

    public function confirmVerify(Request $request) {
        $verify_value   = $request->get('verify_value');
        //$verify_value   = "MTIzNDU2Nzg5OmZ1dHVyZS5zeWcxMTE4QGdtYWlsLmNvbQ==";
        $verify_value   = base64_decode($verify_value);
        $verifys        = explode(":", $verify_value);
        $password       = $verifys[0];
        $email          = $verifys[1];
        $user           = User::where('email', $email)->first();
        $ret = array();
        $request['email']       = $email;
        $request['password']    = $password;
        try {
            // Try to log the user in
            if (Sentinel::authenticate($request->only(['email', 'password']), $request->get('remember-me', false))) {
                //get token and update
                $user = \DB::table('users')->where('id',$user->id)->update(['status'=>'1']);
                $ret['code']    = '200';
                $ret['msg']     = 'This user registered scucessfully.';
                return Response::json($ret);
            }
            $ret['msg'] = 'Can not confirm your account. Please register again.';
        } catch (NotActivatedException $e) {
            $ret['msg'] = 'Can not confirm your account. Please register again.';
        } catch (ThrottlingException $e) {
            $ret['msg'] = 'Can not confirm your account. Please register again.';
        }
        $ret['code']    = '400';
        return Response::json($ret);
    }

    public function reservationSchoolCourse(Request $request) {
        $school_course_id = $request->get('school_course_id');        
        $school_course = \DB::table('school_course')->where('id', $school_course_id)->first(); 
        $ret = array();
        if($school_course) {
            $request['course_id']   = $school_course->course_id;
            $request['level_id']    = $school_course->level_id;                          
            if ($file = $request->file('request_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/courses/reservation';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                $request['athlete_file'] = '/uploads/courses/reservation'.$safeName;
            }
            $req = $request->except('token', 'role', 'request_file') ;
            $reservation = \DB::table('school_course_reservation')
                                ->insert($req);
            if($reservation) {            
                $ret['code']    = '200';
                $ret['msg']     = 'Your request has been sent to the management team correctly. Please wait until approved';                    
            }else{
                $ret['code']    = '400';
                $ret['msg']     = 'Can not reservation. Please retry.';                    
            }
        }else {
            $ret['code']    = '401';
            $ret['msg']     = 'Can not reservation. there is no course registered.';                    
        }
        return Response::json($ret);
    }
}
