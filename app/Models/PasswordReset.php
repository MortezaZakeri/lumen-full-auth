<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class PasswordReset extends Model {

    protected $table = 'password_resets';

    protected $primaryKey = 'email';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    public $timestamps = false;

    public function scopeGetRestPassword($query, $email, $token) {
        return $query->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>=', Carbon::now()->addHours(-1)) //valid for 60 minutes
           ;
    }
}
