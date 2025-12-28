<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class LoginController extends FunctionController
{
    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function employeeLogin()
    {
        //return view('auth.employee-login');
        return view('auth.employee-login', ['plants' => parent::getWarehouses('inventory')]);
    }

    public function managerLogin()
    {
        if (!Auth::check()) {
            return view('auth.manager-login');
        } else{
            return redirect( route('home.dashboard'), 302);
        }

    }

    public function loginEmployee(Request $request)
    {
        $userAttributes = $request->validate([
            'initials' => ['required', 'min:3'],
            'plant' => ['required']
        ]);

        $userAttributes['user_type'] = 1;
        $userAttributes['plant'] = $request->plant;
        $userAttributes['email'] = "user@andronaco.com";
        $userAttributes['first_name'] = "Inventory";
        $userAttributes['last_name'] = "Employee";
        session()->put('location', $request->location);
        session()->put('plant', Plant::find($request->plant)->plant);
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
        session()->put('location', $request->location);
        if(!Auth::attempt($userAttributes)){
            throw ValidationException::withMessages([
                'email' => 'Sorry, these credentials do not match our records.'
            ]);
        }

        Auth::attempt($userAttributes);

        request()->session()->regenerate();
        session()->put('plant', Plant::find(Auth::user()->plant)->plant);

        return redirect('/dashboard');
    }

    public function createManager(Request $request){
        $userAttributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)]
        ]);

        $userAttributes['user_type'] = 2;
        $userAttributes['company'] = "00";

        User::create($userAttributes);
        return redirect('/admin');
    }
}
