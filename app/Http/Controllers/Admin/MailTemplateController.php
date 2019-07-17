<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Theme;
use App\Models\Category;
use App\Models\Article;
use App\Models\User;
use App\Models\UserLog;
use App\Traits\CaptureIpTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Validator;
use DB;

class MailTemplateController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $templates = \DB::table('mail_templates as t')
            ->select('t.*')->orderby('t.id', 'asc')->get();
        return view('admin.mailtemplate.index', compact('templates'));
    }

    public function edit($tempid){
        $template = \DB::table('mail_templates as t')
            ->select('t.*')->where('t.id', $tempid)->first();
        return view('admin.mailtemplate.edit', compact('template'));
    }

    public function create(){
        return view('admin.mailtemplate.new');
    }

    public function update(Request $request){
        $input = Input::only('tempid', 'mailname', 'sender','subject','content');

        $validator = Validator::make($input, [
            'mailname'  => 'required',
            'sender'    => 'required',
            'subject'   => 'required',
            'content'   => 'required',
            'tempid'    => 'required'
        ]);

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $mailname   = $request->input('mailname');
        $subject    = $request->input('subject');
        $sender     = $request->input('sender');
        $content    = $request->input('content');
        $tempid = $request->input('tempid');
        $mailaddr = $request->get('mailaddr', '');
        \DB::table('mail_templates')->where('id', $tempid)->update(['mailname'=>$mailname, 'sender'=>$sender, 'subject'=>$subject, 'content'=>$content]);
        if($mailaddr != ''){
            $template = \DB::table('mail_templates')->where('id', $tempid)->first();
            if(!empty($template)){
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                //$protocol = "https://";
                $domain = $_SERVER['HTTP_HOST'];
                $data = array();
                $subject = $template->subject;
                $message = $template->content;
                $content ='<br><br>'.$message;
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $sender , 'email' => $mailaddr);
                $data[] = $data1;
                $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));
                //session_destroy();
                try {
                    $ch = array();
                    $mh = curl_multi_init();
                    $ch[0] = curl_init();

                    // set URL and other appropriate options
                    curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain . "/mail/school/medkenmail.php");
                    curl_setopt($ch[0], CURLOPT_HEADER, 0);
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
//                    curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
//                    curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
//                    curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
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
            return Redirect::back()->with('testmailsuccess', 'We sent a test email to the email address you entered.');
        }
        return redirect('/mailtemplate/')->with('success', 'Updated successfully.');
    }

    public function store(Request $request){
        $input = Input::only('mailaddr', 'mailname', 'sender','subject','content');

        $validator = Validator::make($input, [
            'mailname'  => 'required|unique:mail_templates,mailname',
            'sender'    => 'required',
            'subject'   => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {

            $this->throwValidationException(
                $request, $validator
            );

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $mailname   = $request->input('mailname');
        $subject    = $request->input('subject');
        $sender     = $request->input('sender');
        $content    = $request->input('content');
        $mailaddr   = $request->get('mailaddr', '');

        $tempid = \DB::table('mail_templates')->insertGetId(['mailname'=>$mailname, 'sender'=>$sender, 'subject'=>$subject, 'content'=>$content]);
        if($mailaddr != ''){
            $template = \DB::table('mail_templates')->where('id', $tempid)->first();
            if(!empty($template)){
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                //$protocol = "https://";
                $domain = $_SERVER['HTTP_HOST'];
                $data = array();
                $subject = $template->subject;
                $message = $template->content;
                $content ='<br><br>'.$message;
                $data1 = array('content' => $content, 'subject' => $subject, 'fromname' => $sender , 'email' => $mailaddr);
                $data[] = $data1;
                $finaldata = array('data' => json_encode($data, JSON_UNESCAPED_UNICODE));
                //session_destroy();
                try {
                    $ch = array();
                    $mh = curl_multi_init();
                    $ch[0] = curl_init();

                    // set URL and other appropriate options
                    curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain . "/mail/school/medkenmail.php");
                    curl_setopt($ch[0], CURLOPT_HEADER, 0);
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
//                    curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
//                    curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
//                    curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
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
            return Redirect::back()->with('testmailsuccess', 'We sent a test email to the email address you entered.');
        }
        return redirect('/mailtemplate/')->with('success', 'Updated successfully.');
    }
}
