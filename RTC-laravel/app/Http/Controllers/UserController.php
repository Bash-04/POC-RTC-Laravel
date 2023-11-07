<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function CreateUser(Request $request)
    {
        if ($request->validate([
            'name' => 'required',
            'email' => 'required',
            //'licenseplate' => 'required',
            'password' => 'required',
        ]) === false) {
            return "Validation Error";
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        
        return $user;
    }

    public function GetUser(Request $request){
        if ($request->validate([
            'email' => 'required',
        ]) === false) {
            return "Validation Error";
        }
        
        $user = User::where('email', $request->email)->first();
        return $user;
    }

    public function GetUsers(Request $request)
    {
        $users = User::all();
        return $users;
    }
}
