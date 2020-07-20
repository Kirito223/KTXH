<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Auth;


class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if(Gate::forUser(Auth::guard('taikhoan')->user())->allows($permission)) {
            return $next($request);   
        } else {
            $previousUrl = url()->previous();
            return redirect()->action('UnauthorizedController@index', ['previousUrl' => $previousUrl]);
        }
        
    }
}
