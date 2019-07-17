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

class ApiSchoolController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->get('status', '5');
        $schools = \DB::table('schools as sc')
            ->leftJoin('users as u', 'u.id', '=', 'sc.user_id')
            ->leftJoin('role_users as ru', 'ru.user_id', '=', 'u.id')
            ->leftJoin('roles as r', 'r.id', '=', 'ru.role_id')
            ->select(['sc.*', 'u.first_name as user_first_name','u.last_name as user_last_name', 'u.email','u.created_at as user_created_at'])
            ->where('r.id','=', 2);

        if($status != '5')
            $schools = $schools->where('sc.status', $status);
        $schools = $schools->orderby('sc.created_at', 'desc')
            ->get();
        return Response::json($schools);
    }

    public function getNames(Request $request) {

        $schools = \DB::table('schools as sc')
            ->where('status' , '1')
            ->select(['sc.id as school_id' , 'sc.name as school_name'])
            ->orderBy('sc.name')
            ->get();
        return Response::json($schools);
    }

    public function getSchoolName(Request $request) {
        $user_id = $request->get('user_id');
        $school = \DB::table('schools as sc')
            ->leftJoin('school_user as su', 'su.school_id', '=', 'sc.id')
            ->where('su.user_id' , $user_id)
            ->select(['sc.id as school_id' , 'sc.name as school_name'])            
            ->first();
        return Response::json($school);
    }


    public function getSchool($id)
    {

        $school = School::find($id);
        $user_id = $school->user_id;
        $school_manager = Sentinel::findUserById($user_id);
        if($school_manager) {
            $school_manager->state_id  = Util::getStateId($school_manager->state);
            $school_manager->region_id = Util::getRegionId($school_manager->region);
            $school_manager->province_id = Util::getProvinceId($school_manager->province);
            $school_manager->country = 'Italy';
            $manager_upload  = DB::table('user_upload')->where('user_id', $user_id)->get();
            $manager_download = DB::table('user_download')->where('user_id', $user_id)->get();
            $school_manager->upload     = $manager_upload;
            $school_manager->download   = $manager_download;

        }
        //$roles = Sentinel::getRoleRepository()->where('id',2)->get();


        $tech_user = false;
        $school_id  = $school->id;

        //get uplaod download data
        $school_upload  = DB::table('school_upload')->where('school_id', $id)->get();
        $school_download = DB::table('school_download')->where('school_id', $id)->get();

        $school->upload     = $school_upload;
        $school->download   = $school_download;

        $role_id    = 3; // technical person
        $tech  = \DB::table('school_user')
            ->where('school_id', $school_id)
            ->where('role_id', $role_id)
            ->first();
        if($tech) $tech_user = true;

        $states = Util::State_list();

        $tech_person = array();

        if($tech_user) {
            $tech_person_id     = $tech->user_id;
            $tech_person        = Sentinel::findUserById($tech_person_id);
            $tech_person->state_id  = Util::getStateId($tech_person->state);
            $tech_person->region_id = Util::getRegionId($tech_person->region);
            $tech_person->province_id = Util::getProvinceId($tech_person->province);
            $tech_person->country = 'Italy';
            $tech_upload  = DB::table('user_upload')->where('user_id', $tech_person_id)->get();
            $tech_download = DB::table('user_download')->where('user_id', $tech_person_id)->get();
            $tech_person->upload     = $tech_upload;
            $tech_person->download   = $tech_download;
        }

        // Show the page
        $ret = array();
        $ret['school']= $school;
        $ret['school_manager'] = $school_manager;
        $ret['technical_person']= $tech_person ;
        $ret['states']           = $states ;
        //$ret['roles']            = $roles ;

        return Response::json($ret);
    }

    public function Create(Request $request)
    {

        $data = new stdClass();
        $ret = array();
        $domain = \Request::root();
        //upload image for school logo file
        if ($file = $request->file('logo_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/schools/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['logo'] = '/uploads/schools/'.$safeName;
        }

        //upload image for school banner profile
        if ($file = $request->file('banner_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/schools/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['banner'] = '/uploads/schools/'.$safeName;
        }


        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/users/'.$safeName;
        }

        $school_state = $request->get('school_state');
        if($school_state)
            $request['school_state'] = Util::getStateName($school_state);
        $school_region = $request->get('school_region');
        if($school_region)
            $request['school_region'] = Util::getRegionName($school_region);
        $school_province = $request->get('school_province');
        if($school_province)
            $request['school_province'] = Util::getProvinceName($school_province);

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
            $req = $request->except('password_confirm', 'group', 'activate', 'logo_file', 'logo', 'school_membership_type',
                                        'banner_file', 'banner', 'pic_file','school_name','reference_asd','company_code',
                                        'school_state', 'school_province','school_region','school_city', 'school_address',
                                        'postal_code', 'school_upload', 'school_download');
            $req['password'] = '123456'; //default password
            $user = Sentinel::register($req, $activate);

            //$role = Sentinel::findRoleById($request->get('group'));
            $role = Sentinel::findRoleById(2); // school manager
            if ($role) {
                $role->users()->attach($user);
            }else {
                DB::rollBack();
            }

            //register Sschool
            if($user){
                $school = School::create([
                    'name'          => $request->input('school_name'),
                    'logo_path'     => $request->input('logo'),
                    'banner_path'   => $request->input('banner'),
                    'reference_asd' => $request->input('reference_asd'),
                    'company_code'  => $request->input('company_code'),
                    'state'         => $request->input('school_state'),
                    'region'        => $request->input('school_region'),
                    'province'      => $request->input('school_province'),
                    'city'          => $request->input('school_city'),
                    'address'       => $request->input('school_address'),
                    'postal_code'   => $request->input('postal_code'),
                    'membership_type' => $request->input('school_membership_type'),
                    'user_id'       => $user->id
                ]);
                //upload and sownload
                if($school) {
                    $school_uploads  = $request->file('school_upload');
                    if($school_uploads) {
                        foreach ($school_uploads as $file) {
                            if ($file) {
                                $type       = $file->extension()?: 'png';
                                $size       = $file->getSize();
                                $destinationPath = public_path() . '/uploads/schools/';
                                $safeName = str_random(10) . '.' . $type;
                                $file->move($destinationPath, $safeName);
                                $path = '/uploads/schools/'.$safeName;
                                $school_upload = \DB::insert('insert into school_upload (school_id, type, url, size)
                                                                    values (?,?,?,?)', [$school->id, $type, $path, $size]);
                            }
                        }
                    }

                    $school_downloads  = $request->file('school_download');
                    if($school_downloads) {
                        foreach ($school_downloads as $file) {
                            if ($file) {
                                $type       = $file->extension()?: 'png';
                                $size       = $file->getSize();
                                $destinationPath = public_path() . '/uploads/schools/';
                                $safeName = str_random(10) . '.' . $type;
                                $file->move($destinationPath, $safeName);
                                $path = '/uploads/schools/'.$safeName;
                                $school_download = \DB::insert('insert into school_download (school_id, type, url, size)
                                                                    values (?,?,?,?)', [$school->id, $type, $path, $size]);
                            }
                        }
                    }
                }


                //add school_user group after check school manager
                $shcool_user_check = \DB::table('school_user')
                    ->where('school_id', $school->id)
                    ->where('role_id', 2)
                    ->where('user_id', $user->id)
                    ->first();

                if(!$shcool_user_check)
                    $shcool_user_check = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?,?)', [$school->id, $user->id, 2]);
            }else {
                DB::rollBack();
            }
            //check for activation and send activation mail if not activated by default
            if (!$request->get('activate')) {
                // Data to be used on the email view
                $data->user_name = $user->first_name .' '. $user->last_name;
                $data->activationUrl = URL::route('activate', [$user->id, Activation::create($user)->code]);

                // Send the activation code through email
//                \Illuminate\Support\Facades\Mail::to($user->email)
//                    ->send(new Restore($data));
            }
            // Redirect to the home page with success menu
            if($school) {
                $this->getSchool($school->id);
            }
            else {
                $ret['code']    = '403';
                $ret['msg']     = 'Can not register. Plese check all required items for school.';
                return Response::json($ret);
            }

            DB::commit();

        } catch (LoginRequiredException $e) {
            $ret['code']    = '404';
            $ret['msg']     = 'Can not register. Plese check all required items.';
            return Response::json($ret);
        } catch (UserExistsException $e) {
            $ret['code']    = '405';
            $ret['msg']     = 'Can not register. This user exist already.';
            return Response::json($ret);
        }
        //send
        $admin = \DB::table('mail_templates')->where('mailname', 'to_manager_school_register')->first();
        $data = array();
        if(!empty($admin)){
            $subject     = $admin->subject;
            $content     = $admin->content;
            $fromname    = $admin->sender;
            $user_name = $user->last_name.' '.$user->first_name;
            $content = str_replace('{user_name}', $user_name, $content);
            $content = str_replace('{domain}', $domain, $content);
            $mail_addresses = [
                'gold@runnerschoolitalia.com',
                'info@runnerschoolitalia.com'
            ];

            foreach ($mail_addresses as $address){
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
                $data[] = $data1;
            }
        }
        // customer
        $customer = \DB::table('mail_templates')->where('mailname', 'to_customer_school_register')->first();

        if(!empty($customer)){
            $subject     = $customer->subject;
            $content     = $customer->content;
            $fromname    = $customer->sender;
            $user_name = $user->last_name.' '.$user->first_name;
            $content = str_replace('{user_name}', $user_name, $content);
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
        $ret['code']    = '200';
        $ret['msg']     = 'Completed successfully!';
        $ret['school']  = $school;
        return Response::json($ret);
    }

    public function updateSchool($school_id, Request $request)
    {
        $ret = array();
        $school = School::find($school_id);
        $original_status = $school->status;
        $current_status = $request->input('status', '0');
        $user_id = $school->user_id;

        $user = \DB::table('users')->where('id', $user_id)->update(['status'=>$current_status]);
        $user = Sentinel::findUserById($user_id);

        $data = new stdClass();
        try {
            // $req = $request->except('user_id','token','password','password_confirm', 'group', 'activate', 'logo_file', 'logo',
            //     'banner_file', 'banner', 'pic_file','school_name','reference_asd','company_code', 'status', 'school_membership_type',
            //     'school_state', 'school_province','school_region','school_city', 'school_address',
            //     'postal_code', 'school_upload', 'school_download');
            // if($user)
            // {
            //      $user->update($req);
            // }

            if ($password = $request->has('password')) {
                $user->password = Hash::make($request->password);
            }else {
                //generate school if $original_status = 0, and
            }

            // is new image for school uploaded?
            if ($file = $request->file('logo_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/schools/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists(public_path() . $school->logo_path)) {
                    File::delete(public_path() . $school->logo_path);
                }
                //save new file path into db
                $school->logo_path = '/uploads/schools/'.$safeName;
            }
            //upload image for school banner profile
            if ($file = $request->file('banner_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/schools/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                if (File::exists(public_path() . $school->banner_path)) {
                    File::delete(public_path() . $school->banner_path);
                }
                $school->banner_path = '/uploads/schools/'.$safeName;
            }

            if(Input::has('school_name')) $school->name   = $request->input('school_name');
            if(Input::has('reference_asd')) $school->reference_asd  =  $request->input('reference_asd');
            if(Input::has('company_code')) $school->company_code   = $request->input('company_code');
            if(Input::has('school_address')) $school->address        = $request->input('school_address');
            if(Input::has('postal_code')) $school->postal_code    = $request->input('postal_code');
            if(Input::has('status')) $school->status         = $request->input('status', '0');
            if(Input::has('school_membership_type')) $school->membership_type = $request->input('school_membership_type');

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
            //state, province, city, ..
            $school_state       = $request->get('school_state');
            if($school_state)
                $school->state      = Util::getStateName($school_state);
            $school_region      = $request->get('school_region');
            if($school_region)
                $school->region     = Util::getRegionName($school_region);
            $school_province    = $request->get('school_province');
            if($school_province)
                $school->province   = Util::getProvinceName($school_province);

            $state              = $request->get('state');
            if($state)
                $user->state        = Util::getStateName($state);
            $region             = $request->get('region');
            if($region)
                $user->region       = Util::getRegionName($region);
            $province           = $request->get('province');
            if($province)
                $user->province     = Util::getProvinceName($province);

            //save school
            $school->save();
            if($school) {
                $school_uploads  = $request->file('school_upload');
                if($school_uploads) {
                    //delete file and database table
                    $school_up_imgs = \DB::table('school_upload')->where('school_id', $school_id)->get();
                    foreach($school_up_imgs as $file) {
                        $file_name = $file ->url;
                        $path = public_path().$file_name;
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                    }
                    $school_up_imgs_delete = \DB::table('school_upload')->where('school_id', $school_id)->delete();
                    foreach ($school_uploads as $file) {
                        if ($file) {
                            $type       = $file->extension()?: 'png';
                            $size       = $file->getSize();
                            $destinationPath = public_path() . '/uploads/schools/';
                            $safeName = str_random(10) . '.' . $type;
                            $file->move($destinationPath, $safeName);
                            $path = '/uploads/schools/'.$safeName;
                            $school_upload = \DB::insert('insert into school_upload (school_id, type, url, size)
                                                                    values (?,?,?,?)', [$school->id, $type, $path, $size]);
                        }
                    }
                }

                $school_downloads  = $request->file('school_download');
                if($school_downloads) {
                    //delete file and database table
                    $school_up_imgs = \DB::table('school_download')->where('school_id', $school_id)->get();
                    foreach($school_up_imgs as $file) {
                        $file_name = $file ->url;
                        $path = public_path().$file_name;
                        if (File::exists($path)) {
                            File::delete($path);
                        }
                    }
                    $school_up_imgs_delete = \DB::table('school_download')->where('school_id', $school_id)->delete();
                    foreach ($school_downloads as $file) {
                        if ($file) {
                            $type       = $file->extension()?: 'png';
                            $size       = $file->getSize();
                            $destinationPath = public_path() . '/uploads/schools/';
                            $safeName = str_random(10) . '.' . $type;
                            $file->move($destinationPath, $safeName);
                            $path = '/uploads/schools/'.$safeName;
                            $school_download = \DB::insert('insert into school_download (school_id, type, url, size)
                                                                    values (?,?,?,?)', [$school->id, $type, $path, $size]);
                        }
                    }
                }
            }
            //save record
            if($user) {
                $user->save();
                // Get the current user groups
                $userRoles = $user->roles()->pluck('id')->all();
                // Get the selected groups
            }
            $selectedRoles = $request->get('groups');

            // have and the groups the user wish to have.
            if($selectedRoles) {
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
            //add school_user group
            if($user) {
                $shcool_user_check = \DB::table('school_user')
                    ->where('school_id', $school->id)
                    ->where('role_id', 2)
                    ->where('user_id', $user->id)
                    ->first();
                if (!$shcool_user_check)
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
                        $data->user_name = $user->first_name . ' ' . $user->last_name;
                        $data->activationUrl = URL::route('activate', [$user->id, Activation::exists($user)->code]);
                        // Send the activation code through email
//                        Mail::to($user->email)
//                            ->send(new Restore($data));
                    }
                }

                // Was the user updated?
                if ($user->save()) {
                    $domain = \Request::root();
                    // Prepare the success message
                    if($original_status != $current_status) {

                        if($current_status == '1') {
                            $customer = \DB::table('mail_templates')->where('mailname', 'to_customer_school_approve')->first();
                        }
                        if($current_status == '2') {
                            $customer = \DB::table('mail_templates')->where('mailname', 'to_customer_school_reject')->first();
                        }
                        $data = array();
                        if(!empty($customer)){
                            $subject     = $customer->subject;
                            $content     = $customer->content;
                            $fromname    = $customer->sender;
                            $user_name = $user->last_name.' '.$user->first_name;
                            $content = str_replace('{user_name}', $user_name, $content);
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
                    }
                    $success = "Updated successfully.";
                    $ret['code'] = '200';
                    $ret['msg'] = $success;
                    return Response::json($ret);
                }
            }

            // Prepare the error message
            $error = 'Can not update. Please check all items.';
            $ret['code']    = '403';
            $ret['msg']     = $error;
            return Response::json($ret);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = 'Can not update. Please check all items.';
            $ret['code']    = '403';
            $ret['msg']     = $error;
            return Response::json($ret);
        }
        return Response::json($ret);
    }


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

    public function createTechnical(Request $request) {
        $data = new stdClass();
        $ret = array();

        $school_id  = $request->get('school_id');
        $school     = School::find($school_id);;
        if(!$school) {
            $ret['code']    = '401';
            $ret['msg']     = 'Plese select school name.';
            return Response::json($ret);
        }
        //upload image for user profile
        if ($file = $request->file('pic_file')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/users/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $request['pic'] = '/uploads/users/'.$safeName;
        }

        $role_id    = '3';
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
            $role = Sentinel::findRoleById('3');//$request->get('group') for technical person
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
                $school_user = \DB::insert('insert into school_user (school_id, user_id, role_id) values (?, ?, ?)', [$school_id, $user->id, $role_id]);

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

    public function updateTechnical($user_id, Request $request) {
        $ret = array();

        $current_status = $request->input('status', '0');
        $user = \DB::table('users')->where('id', $user_id)->update(['status'=>$current_status]);
        $user = Sentinel::findUserById($user_id);

        $data = new stdClass();
        DB::beginTransaction();
        try {
            // $user->update($request->except('user_id','token','pic_file','school_id','password','password_confirm','groups',
            //                                     'activate','user_upload', 'user_download'));

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

            $selectedRoles = ['3']; // $request->get('groups'); 3 is technical manager

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
            $school = School::find($school_id);
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
        }
        DB::rollBack();
        $ret['code']    = 401;
        $ret['msg']     = $error;
        return Response::json($ret);
    }

}
