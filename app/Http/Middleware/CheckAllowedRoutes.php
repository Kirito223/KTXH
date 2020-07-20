<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Gate;
use Closure;
use Auth;
class CheckAllowedRoutes
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
        if(Gate::forUser(Auth::guard('taikhoan')->user())->allows($request->segment(1))) {
            return $next($request);   
        } else {
            $previousUrl = url()->previous();
            return redirect()->action('UnauthorizedController@index', ['previousUrl' => $previousUrl]);
        }
    }
}
