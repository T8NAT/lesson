<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$guards): Response
    {
//        $guards = empty($guards) ? ['student','teacher', 'admin','user'] : $guards;
//        foreach ($guards as $guard) {
//            if (Auth::guard($guard)->check()) {
//                $user = Auth::guard($guard)->user();
//                if ($user->role_id == 1){
//                 return redirect()->route('dashboard');
//                }elseif ($user->type == 'teacher'){
//                    return  redirect('/') ;
//                }else{
//                    return redirect('/');
//                }
//            }
//        }
//        return $next($request);
//    }

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user(); // or Auth::guard($guard)->user();
                if ($user->role_id == 1) {
                    return redirect()->route('dashboard');
                } elseif ($user->type == 'teacher') {
                    return redirect('/teacher/dashboard');
                } else {
                    return redirect('/student/dashboard');
                }
            }
        }

        return $next($request);
    }

}
