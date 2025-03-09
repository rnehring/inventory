<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function employeeLogin()
    {
        return view('employee-login');
    }

    public function managerLogin()
    {
        return view('manager-login');

    }


    public function loginEmployee(Request $request)
    {
        $userAttributes = $request->validate([
            'initials' => ['required'],
            'companyCode' => ['required']
        ]);
    }


    public function loginManager(Request $request)
    {
        $userAttributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'password']
        ]);
    }
}
