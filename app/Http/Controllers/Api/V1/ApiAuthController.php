<?php namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ConfirmPasswordRequest;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
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
use App\Models\User;
use App\Http\Controllers\JoshController;
use Response;


class ApiAuthController extends JoshController
{
      
    /**
     * Account sign in form processing.
     * @param Request $request
     * @return Redirect
     */
    public function postSignin(Request $request)
    {
        $ret = array();
        $user = User::where('email', $request->email)->where('status', '1')->first();
        $user_id= 0;
        $token  = '';
        $role   = 0;
        if($user) {
            $user_id    = $user->id;
            $role_obj   = \DB::table('role_users')->where('user_id', $user_id)->first();
            $role       = $role_obj->role_id;
            $token      = $this->getToken();
        }else {
            $ret['token']   = $token;
            $ret['role']    = $role;
            $ret['user_id'] = $user_id; 
            $ret['code']    = '400' ;
            $ret['msg']     = trans('auth/message.account_not_found');
            return Response::json($ret);
        } 
        try {
            // Try to log the user in
            if (Sentinel::authenticate($request->only(['email', 'password']), $request->get('remember-me', false))) {            
                //get token and update            
		        $user = \DB::table('users')->where('id',$user_id)->update(['token'=>$token]);
                $ret['token']   = $token;
                $ret['role']    = $role;
                $ret['user_id'] = $user_id; 
                $ret['code']    = '200';
                $ret['msg'] =  trans('auth/message.signin.success');
                return Response::json($ret);	    
            }
            $ret['msg'] = trans('auth/message.account_not_found');
        } catch (NotActivatedException $e) {
            $ret['msg'] = trans('auth/message.account_not_activated');
        } catch (ThrottlingException $e) {
            $ret['msg'] = trans('auth/message.account_suspended');
        }
        $ret['token']   = '';
        $ret['role']   = 0;
        $ret['user_id'] = 0; 
        $ret['code']    = '400';
        return Response::json($ret);        
    }

    public function getToken() {
		$cu_date = date("Y-m-d H:i:s", strtotime("now"));
		$rand = str_random(20);
		$date = strtotime($cu_date).$rand;		
		$token = base64_encode($date);			
		return $token;
	}

    /**
     * Account sign up form processing.
     *
     * @return Redirect
     */
    public function postSignup(UserRequest $request)
    {
        $ret = array();

        try {
            // Register the user
            $user = Sentinel::registerAndActivate([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            //add user to 'User' group
            $role = Sentinel::findRoleById(2);
            $role->users()->attach($user);

            // Log the user in
            Sentinel::login($user, false);
            $ret['code']    = 200;
            $ret['msg']     = trans('auth/message.signin.success');    
            return Response::json($ret);

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        $ret['code']    = 400;
        $ret['msg']     = $this->messageBag;   
        return Response::json($ret); 

    }

    /**
     * User account activation page.
     *
     * @param number $userId
     * @param string $activationCode
     * @return
     */
    public function getActivate($userId,$activationCode = null)
    {
        $ret = array();
        // Is user logged in?
        if (Sentinel::check()) {
            return Redirect::route('dashboard');
            $ret['code']    = 200;
            $ret['msg']     = 'Activated status';
            return Response::json($ret);
        }
        $user = Sentinel::findById($userId);
        $activation = Activation::create($user);

        if (Activation::complete($user, $activation->code)) {
            // Activation was successful
            // Redirect to the login page
            $ret['code']    = 200;
            $ret['msg']     = trans('auth/message.activate.success');
            return Response::json($ret);
        } else {
            // Activation not found or not completed.
            $ret['code']    = 400;
            $ret['msg']     = trans('auth/message.activate.error');
            return Response::json($ret);            
        }

    }

    /**
     * Forgot password form processing page.
     * @param Request $request
     *
     * @return Redirect
     */
    public function postForgotPassword(ForgotRequest $request)
    {
        $data = new stdClass();
        $ret = array();
        try {
            // Get the user password recovery code
            $user = Sentinel::findByCredentials(['email' => $request->get('email')]);

            if (!$user) {
                $ret['code']    = '400';                
                $ret['msg']     = trans('auth/message.account_email_not_found');
                return Response::json($ret);                
            }
            $activation = Activation::completed($user);
            if(!$activation){
                $ret['code']    = '400';
                $ret['msg']     = trans('auth/message.account_not_activated');
                return Response::json($ret);                                
            }
            $reminder = Reminder::exists($user) ?: Reminder::create($user);
            // Data to be used on the email view

            $data->user_name = $user->first_name .' ' .$user->last_name;
            $data->forgotPasswordUrl = URL::route('forgot-password-confirm', [$user->id, $reminder->code]);

            // Send the activation code through email

            Mail::to($user->email)
                ->send(new ForgotPassword($data));

        } catch (UserNotFoundException $e) {
            // Even though the email was not found, we will pretend
            // we have sent the password reset code through email,
            // this is a security measure against hackers.
        }

        //  Redirect to the forgot password
        $ret['code']    = '200';
        $ret['msg']     = trans('auth/message.forgot-password.success');
        return Response::json($ret);                                        
    }

    /**
     * Forgot Password Confirmation page.
     *
     * @param number $userId
     * @param  string $passwordResetCode
     * @return View
     */
    public function getForgotPasswordConfirm($userId,$passwordResetCode = null)
    {
        $ret = array();
        // Find the user using the password reset code        
        if(!$user = Sentinel::findById($userId)) {
            // Redirect to the forgot password page
            $ret['code']    = 400;
            $ret['msg']     = trans('auth/message.account_not_found');
            return Response::json($ret);            
        }
        if($reminder = Reminder::exists($user)) {
            if($passwordResetCode == $reminder->code) {
                $ret['code']    = 200;
                $ret['msg']     = 'Completed Successfully.';
                return Response::json($ret);                            
            } else{
                $ret['code']    = 400;
                $ret['msg']     = 'Code does not match';
                return Response::json($ret);                                            
            }
        } else {
            $ret['code']    = 400;
            $ret['msg']     = 'Does not exists';
            return Response::json($ret);                                        
        }

        // Show the page
        // return View('admin.auth.forgot-password-confirm');
    }

    /**
     * Forgot Password Confirmation form processing page.
     *
     * @param Request $request
     * @param number $userId
     * @param  string   $passwordResetCode
     * @return Redirect
     */
    public function postForgotPasswordConfirm(ConfirmPasswordRequest $request, $userId, $passwordResetCode = null)
    {

        // Find the user using the password reset code
        $user = Sentinel::findById($userId);
        if (!$reminder = Reminder::complete($user, $passwordResetCode, $request->get('password'))) {
            // Ooops.. something went wrong
            return Redirect::route('signin')->with('error', trans('auth/message.forgot-password-confirm.error'));
        }

        // Password successfully reseted
        return Redirect::route('signin')->with('success', trans('auth/message.forgot-password-confirm.success'));
    }

    /**
     * Logout page.
     *
     * @return Redirect
     */
    public function getLogout(Request $request)
    {
        $user = Sentinel::getUser();
        if($user)  Sentinel::logout($user);
        //change token
        $user_id = $request->get('user_id');
        $token = $this->getToken();
        $userupdate = \DB::table('users')->where('id', $user_id)->update(['token' => $token]);


        $ret = array();
        $ret['code']    = 200;
        $ret['msg']     = 'You have successfully logged out!';
        return Response::json($ret);        
    }

    /**
     * Account sign up form processing for register2 page
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postRegister2(UserRequest $request)
    {

        try {
            // Register the user
            $user = Sentinel::registerAndActivate(array(
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ));

            //add user to 'User' group
            $role = Sentinel::findRoleById(2);
            $role->users()->attach($user);

            // Log the user in
            Sentinel::login($user, false);

            // Redirect to the home page with success menu
            return Redirect::route("dashboard")->with('success', trans('auth/message.signup.success'));

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    public function passwordreset($id, Request $request)
    {
        $user = Sentinel::findUserById($id);
        $password = $request->get('password');
        $user->password = Hash::make($password);
        $user->save();
    }

}
