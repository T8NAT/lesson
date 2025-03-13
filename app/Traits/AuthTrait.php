<?php
namespace App\Traits;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait AuthTrait{
    public function checkGuard($request){
        if ($request->type == 'student'){
            $guardName='student';
        }
        elseif ($request->type == 'teacher'){
            $guardName='teacher';
        }else{
            $guardName='admin';
        }
        return $guardName;
    }
    public function redirect($request){
        if ($request->type == 'teacher'){
            $teacher = Auth::guard('teacher')->user();
            if ($teacher->status == 'active'){
                    return redirect(route('dashboard'));
        }   elseif ($teacher->status == 'inactive'){
                return redirect(route('login'));
            }else{
                return redirect(route('login'));
            }
        }elseif ($request->type == 'student'){
            $student = Auth::guard('student')->user();
            if ($student->status == 'active'){
                return redirect(route('dashboard'));
            }elseif ($student->status == 'inactive'){
                return redirect(route('login'));
            }else{
                return redirect(route('login'));
            }
        }
    }
}
