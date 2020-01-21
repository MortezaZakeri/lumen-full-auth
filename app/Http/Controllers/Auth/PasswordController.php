<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResetPasswordMailable;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller {

    public function forgot(Request $request) {
        $this->validate($request, [
            'email' => 'email|required'
        ]);
        $credentials = $request->only(['email']);
        $user = User::where('email', $credentials['email'])->first();
        if (isset($user)) {
            $token = Str::random(256);
            $reset = PasswordReset::firstOrNew(['email' => $user->email]);
            $reset->token = $token;
            $reset->created_at = date('Y-m-d H:i:s');
            $reset->save();
            Mail::to($user->email)->send(new ResetPasswordMailable($user->email, $token));
            return response()->json(['done'], 201);
        }
        return response()->json(['error' => 'Error in supplied data'], 400);
    }

    public function sendResetPassword(Request $request) {
        if ($request->has('token') && $request->has('email')) {
            $exist = PasswordReset::getRestPassword($request['email'], $request['token'])->first();

            if ($exist) {
                return response()->json([
                    'token' => $exist->token,
                    'email'=> $exist->email
                ]);
            }
        }
        return response()->json(['error' => 'Error in supplied data'], 400);
    }

    public function setNewPassword(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6|max:255',
            'token' => 'required|string|min:100'
        ]);
        if ($request->has('token') && $request->has('email')) {
            $exist = PasswordReset::getRestPassword($request['email'], $request['token'])->first();
            if (isset($exist)) {
                $user = User::where('email', $request['email'])->first();
                $user->password = app('hash')->make($request['password']);
                $user->confirmed_at = Carbon::now();
                $user->save();
                $exist->delete();
                return response()->json(['done'], 201);
            }
        }
        return response()->json(['error'], 401);
    }


}
