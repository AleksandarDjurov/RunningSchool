<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ConfirmPasswordRequest;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\Redirect;
use Mail;
use Reminder;
use Sentinel;
use URL;
use Validator;
use View;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ForgotRequest;
use stdClass;
use App\Mail\ForgotPassword;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;




class ApiContactController extends Controller
{

    public function test(Request $request) {
        $ret = array();
        //upload image for user profile
        $accept_key =  str_random(10);
        $contact = Contact::create([
            'first_name'   => 'First',
            'last_name'    => 'Last',
            'role'         => '2',
            'comment'      => 'this is comment',
            'email'        => 'future.syg1118@gmail.com',
            'phone'        => '1234567',
            'address'      => 'this is address',
            'birthday'     => '1990-09-01',
            'accept_key'   => $accept_key,
        ]);

        $role = \DB::table('roles')->where('id','2' )->first();
        $role_name = $role->name;

        //send mail to manager
        $manager = Sentinel::findById('1');
        $manager_email = $manager->email;
        $contact_admin = \DB::table('mail_templates')->where('mailname', 'to_manager_contact')->first();
        $data = array();
        if(!empty($contact_admin)){
            $subject     = $contact_admin->subject;
            $content     = $contact_admin->content;
            $fromname    = $contact_admin->sender;
            $user_name = $contact->last_name.' '.$contact->first_name;
            $content = str_replace('{user_name}', $user_name, $content);
            $content = str_replace('{first_name}', $contact->first_name, $content);
            $content = str_replace('{last_name}', $contact->last_name, $content);
            $content = str_replace('{email}', $contact->email, $content);
            $content = str_replace('{phone}', $contact->phone, $content);
            $content = str_replace('{role}', $role_name, $content);
            $content = str_replace('{comment}', $contact->comment, $content);
            //$content = str_replace('{key}', $accept_key, $content);
            $mail_addresses = [
                'future.syg1118@gmail.com',
                'gold@runnerschoolitalia.com',
            ];

            foreach ($mail_addresses as $address){
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
                $data[] = $data1;
            }
        }
        // send to customer
        $contact_customer = \DB::table('mail_templates')->where('mailname', 'to_customer_contact')->first();
        //$data = array();
        if(!empty($contact_customer)){
            $subject     = $contact_customer->subject;
            $content     = $contact_customer->content;
            $fromname    = $contact_customer->sender;
            $user_name = $contact->last_name.' '.$contact->first_name;
            $content = str_replace('{user_name}', $user_name, $content);
            $content = str_replace('{first_name}', $contact->first_name, $content);
            $content = str_replace('{last_name}', $contact->last_name, $content);
            $content = str_replace('{email}', $contact->email, $content);
            $content = str_replace('{phone}', $contact->phone, $content);
            $content = str_replace('{role}', $role_name, $content);
            $content = str_replace('{comment}', $contact->comment, $content);
            //$content = str_replace('{key}', $accept_key, $content);
            $mail_addresses = [
                $contact->email, // guest email
            ];

            foreach ($mail_addresses as $address){
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
                $data[] = $data1;
            }
        }

        $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));

        if($contact) {
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
        if($contact) {
            $ret['code'] = '200' ;
            $ret['msg'] = 'Registered successfully';
        }else {
            $ret['code'] = '400';
            $ret['msg'] = 'Error, Please again';
        }
        return Response::json($ret);
    }


    public function addContact(Request $request) {
       $ret = array();
       $domain = \Request::root();
       //upload image for user profile
       $picture = '';
        if ($file = $request->file('pic')) {
            $extension = $file->extension()?: 'png';
            $destinationPath = public_path() . '/uploads/contacts/';
            $safeName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $safeName);
            $picture = '/uploads/contacts/'.$safeName;
        }
        $accept_key =  str_random(10);
        $contact = Contact::create([        
            'first_name'   => $request->input('first_name'),
            'last_name'    => $request->input('last_name'),
            'pic'          => $picture,
            'role'         => $request->input('role'),
            'comment'      => $request->input('comment'),        
            'email'        => $request->input('email'),
            'phone'        => $request->input('phone'),
            'address'      => $request->input('address'),        
            'birthday'     => $request->input('birthday'),
            'accept_key'   => $accept_key,
            ]);

        $role = \DB::table('roles')->where('id',$request->input('role') )->first();
        if($role)
            $role_name = $role->name;
        else
            $role_name = '';
        //send mail to manager
        $manager = \DB::table("users")->where('id', '1')->first();
        $manager_email = $manager->email;
        $contact_admin = \DB::table('mail_templates')->where('mailname', 'to_manager_contact')->first();
        $data = array();
       if(!empty($contact_admin)){
           $subject     = $contact_admin->subject;
           $content     = $contact_admin->content;
           $fromname    = $contact_admin->sender;
           $user_name = $contact->last_name.' '.$contact->first_name;
           $content = str_replace('{user_name}', $user_name, $content);
           $content = str_replace('{first_name}', $contact->first_name, $content);
           $content = str_replace('{last_name}', $contact->last_name, $content);
           $content = str_replace('{email}', $contact->email, $content);
           $content = str_replace('{phone}', $contact->phone, $content);
           $content = str_replace('{role}', $role_name, $content);
           $content = str_replace('{comment}', $contact->comment, $content);
           $content = str_replace('{domain}', $domain, $content);
           //$content = str_replace('{key}', $accept_key, $content);
           $mail_addresses = [
               'gold@runnerschoolitalia.com',
               'info@runnerschoolitalia.com'
           ];

           foreach ($mail_addresses as $address){
               $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
               $data[] = $data1;
           }
       }
        // send to customer
       $contact_customer = \DB::table('mail_templates')->where('mailname', 'to_customer_contact')->first();

       if(!empty($contact_customer)){
           $subject     = $contact_customer->subject;
           $content     = $contact_customer->content;
           $fromname    = $contact_customer->sender;
           $user_name = $contact->last_name.' '.$contact->first_name;
           $content = str_replace('{user_name}', $user_name, $content);
           $content = str_replace('{first_name}', $contact->first_name, $content);
           $content = str_replace('{last_name}', $contact->last_name, $content);
           $content = str_replace('{email}', $contact->email, $content);
           $content = str_replace('{phone}', $contact->phone, $content);
           $content = str_replace('{role}', $role_name, $content);
           $content = str_replace('{comment}', $contact->comment, $content);
           $content = str_replace('{domain}', $domain, $content);
           //$content = str_replace('{key}', $accept_key, $content);
           $mail_addresses = [
               $contact->email, // guest email
           ];

           foreach ($mail_addresses as $address){
               $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
               $data[] = $data1;
           }
       }

       $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));

       if($contact) {
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
       if($contact) {
           $ret['code'] = '200' ;
           $ret['msg'] = 'Registered successfully';
       }else {
            $ret['code'] = '400';
            $ret['msg'] = 'Error, Please again';
       }  
       return Response::json($ret);   
   }

   public function getContactList(Request $request) {
        $role = $request->get('role');
        if($role == 1) {
            return Response::json(array());
        }else {
            if($role == 0)
                $contact = Contact::all();
            else
                $contact = Contact::where('role', $role)->get();
            return Response::json($contact);
        }
   }

   public function getUser($contact_id) {
       $contact = Contact::find($contact_id);
       return Response::json($contact);
   }

    public function clearContactList(Request $requestm, $contact_id) {
       $deleted_date = $timestamp = date("Y-m-d H:i:s");
       if($contact_id == '0')
            $contact = \DB::table('contact')->update(['status' => 2, 'deleted_at', $deleted_date]);
       else
           $contact = \DB::table('contact')->where('id', $contact_id)->update(['status' => 2, 'deleted_at' => $deleted_date]);
        $ret = array();
        $ret['code'] = '200' ;
        $ret['msg'] = 'Deleted successfully';
       return Response::json($ret);
    }

    public function contactConfirm(Request $request) {
       $ret = array();
       $confirm = '0';
       $key = $request->get('contact_key');
       $role_id = $request->get('role_id', 0);
       $contact = \DB::table('contact')
                        ->where('accept_key', $key)
                        ->where('role', $role_id)
                        ->first();
       if($contact) $confirm = '1';
       $ret['code'] = 200;
       $ret['confirm'] = $confirm;
       return Response::json($ret);
    }

    public function sendApproved(Request $request) {
        $ret = array();
        $id = $request->get('contact_id');
        $contact = Contact::find($id);
        if($contact) {
            $role = \DB::table('roles')->where('id', $contact->role)->first();
            $role_name = $role->name;
            $accept_key = $contact->accept_key;
            if(empty($accept_key)) {
                $accept_key =  str_random(10);
                $contact_update = \DB::table('contact')->where('id' , $id)->update(['status'=>1, 'accept_key' => $accept_key]);
            }
            else
                $contact_update = \DB::table('contact')->where('id' , $id)->update(['status'=>1]);
            // send to customer
            $contact_customer = \DB::table('mail_templates')->where('mailname', 'to_customer_contact_approved')->first();
            $data = array();
            if (!empty($contact_customer)) {
                $subject = $contact_customer->subject;
                $content = $contact_customer->content;
                $fromname = $contact_customer->sender;
                $user_name = $contact->last_name . ' ' . $contact->first_name;
                $content = str_replace('{user_name}', $user_name, $content);
                $content = str_replace('{first_name}', $contact->first_name, $content);
                $content = str_replace('{last_name}', $contact->last_name, $content);
                $content = str_replace('{email}', $contact->email, $content);
                $content = str_replace('{phone}', $contact->phone, $content);
                $content = str_replace('{role}', $role_name, $content);
                $content = str_replace('{comment}', $contact->comment, $content);
                $content = str_replace('{key}', $accept_key, $content);
                $mail_addresses = [
                    $contact->email, // guest email
                ];

                foreach ($mail_addresses as $address) {
                    $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $fromname, 'email' => $address);
                    $data[] = $data1;
                }
            }

            $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));


                try {
                    $ch = array();
                    $mh = curl_multi_init();
                    $ch[0] = curl_init();

                    $base_url = url('/');
                    curl_setopt($ch[0], CURLOPT_URL, $base_url . "/mail/school/schoolmail.php");
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
                $ret['code'] = '200';
                $ret['msg'] = 'Updated successfully';

        }else {
            $ret['code'] = '400';
            $ret['msg'] = 'Error, Please again';
        }

        return Response::json($ret);
    }
}
