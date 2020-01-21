<?php
/**
 * User: Morteza Zakeri
 * Date: 1/21/2020
 */

namespace App\Http\Middleware\Permissions;

use App\Http\Middleware\AuthMiddleware;
use Closure;

class UserMiddleware extends AuthMiddleware {

    public function handle($request, Closure $next) {
        return $this->handleRequest($request, $next, [1, 2, 3]);
    }
}
