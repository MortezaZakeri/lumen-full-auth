<?php
/**
 * User: Morteza Zakeri
 * Date: 1/21/2020
 */

namespace App\Http\Controllers;


use Illuminate\Mail\Mailable;

class ResetPasswordMailable extends Mailable {


    private $token;
    private $email;

    public function __construct($email, $token) {
        $this->token = $token;
        $this->email = $email;
    }

    public function build() {

        return $this
            ->view('reset_password')
            ->subject('Password Reset Link')
            ->with([
                'token' => $this->token,
                'email' => $this->email
            ]);
    }

}
