<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
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

    public function newUser(Request $request){
        return view('users.new');
    }

    public function getUser($id){
        $user = DB::select('
        SELECT * FROM `users` WHERE id = ?', [$id]);
        return $user[0];
    }

    public function update(Request $request){
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'initials' => 'required|string|max:10',
            'email' => 'required|email',
            'password' => 'nullable|min:6|confirmed',
            'company' => 'required',
            'user_type' => 'required',
        ]);
        //dd($validated);
        $user = User::findOrFail($request->id);

        // Update user fields
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->initials = $validated['initials'];
        $user->email = $validated['email'];
        $user->company = $validated['company'];
        $user->user_type = $validated['user_type'];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function new(Request $request){
        $userAttributes = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'initials' => 'required|string|max:10',
            'email' => 'required|email',
            'password' => ['required', Password::min(6)],
            'company' => 'required',
            'user_type' => 'required',
        ]);

        $user = User::create($userAttributes);

        return redirect()->route('users.index')->with('success', 'User added successfully');
    }

    public function deleteUser(Request $request){
        $user = User::findOrFail($request->id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
