<?php
/**
 * Created by PhpStorm.
 * User: HP i5
 * Date: 9/07/15
 * Time: 12:54
 */

namespace aidocs\Http\Controllers;


use Carbon\Carbon;
use aidocs\User;

class UsersController extends Controller {

    public function getOrm()
    {
        $users = User::select('id','first_name')
            ->with('profile')
            ->where('first_name','<>','Miguel')
            ->orderBy('first_name','DESC')
            ->get();
        dd($users->toArray());
    }

    public function getDep()
    {
        $user = User::first();
        dd($user->workplace->depDsc);
    }

    public function  getIndex()
    {
        $result = \DB::table('users')
            ->select(
                'users.*',
                'user_profiles.id as profile_id',
                'user_profiles.twitter',
                'user_profiles.birthdate'
            )
            ->orderBy(\DB::raw('RAND()'))
            ->leftjoin('user_profiles','users.id','=','user_profiles.user_id')
            ->get();

        foreach($result as $row)
        {
            $row->full_name = $row->first_name . ' ' . $row->last_name;
            $row->age = Carbon::parse($row->birthdate)->age;
        }

        dd($result);
        return $result;
    }
} 