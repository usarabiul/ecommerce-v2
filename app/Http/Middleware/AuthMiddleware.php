<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && url('log-out')!==$request->url()){
            return redirect()->route('admin.dashboard');
        }
        return $next($request);
    }

    
}
