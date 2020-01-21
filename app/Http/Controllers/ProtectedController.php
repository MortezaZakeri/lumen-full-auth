<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class ProtectedController extends Controller {

    public function index() {
        return ['protected data'];
    }

}
