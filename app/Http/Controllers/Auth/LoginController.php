<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends AppController {

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8'
        ]);
        $credentials = $request->only(['email', 'password']);
        $token = Auth::attempt($credentials);

        if ($token) {
            return response()->json(
                (new TokenController())->tokenResponse($token)
            );
        }
        return response()->json(['error'], 401);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Successfully logged out'
            ]
        ]);
    }
}
