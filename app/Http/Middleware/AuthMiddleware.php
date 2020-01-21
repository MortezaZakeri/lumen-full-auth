<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param array $accessList
     * @return mixed
     */
    public function handleRequest($request, Closure $next, array $accessList) {

        if ($user = $this->authenticated()) {
            if ($this->authorized($user, $accessList)) {
                return $next($request);
            } else {
                return response()->json(['error' => 'unauthorized'], 403);
            }
        } else {
            return response()->json(['error' => 'unauthenticated'], 401);
        }
    }

    private function authenticated(): ?User {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return null;
            }
        } catch (TokenExpiredException $e) {
            // If the token is expired, then it will be refreshed and added to the headers
            try {
                $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                $user = JWTAuth::setToken($refreshed)->toUser();
                header('Authorization: Bearer ' . $refreshed);
            } catch (JWTException $e) {
                return null;
            }
        } catch (JWTException $e) {
            return null;
        }
        // Login the user instance for global usage
        Auth::login($user, false);
        return $user;
    }

    private function authorized($user, array $accessList = []) {
        return isset($user) && in_array($user->permission_id, $accessList);
    }
}
