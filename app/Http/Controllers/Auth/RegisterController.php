<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller {

    public function register(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        try {
            $user = new User;
            $user->name = $request['name'];
            $user->email =$request['email'];
            $user->password = app('hash')->make($request['password']);
            $user->save();
            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }
}
