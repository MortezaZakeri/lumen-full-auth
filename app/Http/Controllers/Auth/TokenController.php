<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenController extends Controller {

    public function refresh(Request $request) {
        try {
            if ($request->has('token') && $token = Auth::refresh()) {
                JWTAuth::setToken($token)->toUser();
                return $this->tokenResponse($token);
            }
        } catch (Exception $exception) {
        }
        return response()->json(['error' => 'unauthenticated'], 401);
    }


    public function userVerify(Request $request, $token) {

    }

    public function tokenResponse(string $token) {
        return [
            'token' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ],
            'user' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ];
    }
}
