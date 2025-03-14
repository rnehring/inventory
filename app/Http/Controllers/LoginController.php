<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    //
    public function employeeLogin()
    {
        return view('auth.employee-login');
    }

    public function managerLogin()
    {
        return view('auth.manager-login');

    }

    public function loginEmployee(Request $request)
    {
        $userAttributes = $request->validate([
            'initials' => ['required', 'min:3'],
            'companyCode' => ['required']
        ]);

        $userAttributes['user_type'] = 1;
        $userAttributes['company'] = $request->companyCode;

        if(in_array($request->companyCode, self::KENTWOOD_COMPANIES )){
            $userAttributes['location'] = "Kentwood";
        }
        else{
            $userAttributes['location'] = "Houston";
        }

        unset($userAttributes['companyCode']);

        $user = User::firstOrCreate($userAttributes);

        Auth::login($user);

        return redirect('/count');
    }

    public function loginManager(Request $request)
    {
        $userAttributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)]
        ]);

        $userAttributes['user_type'] = 2;
        $userAttributes['company'] = "00";
        $userAttributes['location'] = "Kentwood";

        if(!Auth::attempt($userAttributes)){
            throw ValidationException::withMessages([
                'email' => 'Sorry, these credentials do not match our records.'
            ]);
        }

        Auth::attempt($userAttributes);

        request()->session()->regenerate();

        return redirect('/dashboard');
    }

    public function createManager(Request $request){
        $userAttributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)]
        ]);

        $userAttributes['user_type'] = 2;
        $userAttributes['company'] = "00";
        $userAttributes['location'] = "Kentwood";

        $user = User::create($userAttributes);
        return redirect('/admin');
    }
}
