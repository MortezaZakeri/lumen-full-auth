<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$VERSION = 'api/v1';

$router->group(['prefix' => $VERSION], function ($router) {

    $router->post('register', 'Auth\RegisterController@register');

    $router->post('login', 'Auth\LoginController@login');

    $router->get('logout', 'Auth\LoginController@logout');

    $router->post('refresh', 'Auth\TokenController@refresh');

    $router->post('forgot_password', 'Auth\PasswordController@forgot');

    $router->get('send_reset', 'Auth\PasswordController@sendResetPassword');

    $router->post('set_new_password', 'Auth\PasswordController@setNewPassword');

    $router->group(['middleware' => 'admin'], function ($router) {
        $router->get('secure', 'ProtectedController@index');
    });

});
