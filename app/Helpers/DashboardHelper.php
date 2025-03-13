<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

//function getUsers(): Collection|array
//{
//    return User::query()->where('role_id', '=',1)->latest()->get();
//
//}
//function getStudentsBerMonth(): Collection|array
//{
//    return User::query()->where('type', '=','student')->where('created_at','>=',Carbon::now()->subDay(30))->latest()->limit(10)->get();
//}
//function getTeachersBerMonth(): Collection|array
//{
//    return User::query()->where('type', '=','teacher')->where('created_at','>=',Carbon::now()->subDay(30))->latest()->limit(10)->get();
//}
//function StudentsBerWeak(): Collection|array
//{
//    return User::query()->where('type', '=','student')->where('created_at','>=',Carbon::now()->subDay(7))->latest()->limit(10)->get();
//}
//function getStudentsBerDay(): Collection|array
//{
//    return User::query()->where('type', '=','student')->where('created_at','>=',Carbon::now())->latest()->limit(10)->get();
//}



