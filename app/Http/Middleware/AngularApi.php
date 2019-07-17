<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Response;

class AngularApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user_id   = $request->get('user_id');
        $token     = $request->get('token');
        $role      = $request->get('role');

        $user = \DB::table('users')
            ->where('token', $token)
            ->where('id', $user_id)
            ->first();
        $ret = array();
        if(empty($user)) {
            $ret['code']   = '403';
            $ret['msg']    = 'You can not connect. Please reconnet with your right persmission.';
            return Response::json($ret);
        }
        return $next($request);
    }
}
