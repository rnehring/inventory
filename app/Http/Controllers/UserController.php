<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends FunctionController
{

    public $tableName;
    public function __construct()
    {
        parent::__construct();
        if(session()->get('location') == "Kentwood"){
            $this->tableName = "inventory";
        }
        else{
            $this->tableName = "inventory_houston";
        }
    }

    public function index(){
        return view('users.index',['users' => User::all() ]);
    }

    public function editUser(Request $request){
        return view('users.edit',['user' => $this->getUser($request->id) ]);
    }

    public function getUser($id){
        $user = DB::select('
        SELECT * FROM `users` WHERE id = ?', [$id]);
        return $user[0];
    }

    public function updateUser(Request $request){
        $userAttributes = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'initials'  => ['required', 'string', 'max:3'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)],
            'company' => ['required', 'string', 'max:4'],
            'userType' => ['required']
        ]);

        DB::update("
        UPDATE users
        SET first_name = ?,
            last_name = ?,
            initials = ?,
            email = ?,
            password = ?,
            company = ?,
            user_type = ?",
        [$request->first_name, $request->last_name, $request->initials, $request->email, Hash::make($request->password), $request->company, $request->user_type]);

        //return view('users.index',['users' => User::all(), 'updated' => true, 'updated_user_name' => $request->first_name . $request->last_name ]);

        return redirect()->route('users.index',['users' => User::all(), 'updated' => true, 'updated_user_name' => $request->first_name . $request->last_name ] );


    }

    public function newUser(Request $request){

    }

    public function deleteUser(Request $request){

    }
}
