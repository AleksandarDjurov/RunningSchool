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

use App\Http\Controllers\Controller;
use Response;




class ApiGroupController extends Controller
{

    public function group() {
        $roles = Sentinel::getRoleRepository()->all();
        $ret = array();
        foreach ($roles as $role) {
            if($role->id == '1') continue;
            $one = new \stdClass();
            $one->id = $role->id;
            $one->slug = $role->slug;
            $one->name = $role->name;
            $persons = \DB::table('school_user')->where('role_id', $role->id)->count();
            $one->persons = $persons;
            $ret[] = $one;
        }
        return Response::json($ret);
    }
}
