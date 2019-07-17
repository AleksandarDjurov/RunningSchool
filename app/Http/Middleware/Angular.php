<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class Angular
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
        if(empty($user)) {
            return redirect('/')->with('info', 'You must be logged in!');
        }
        return $next($request);
    }
}
