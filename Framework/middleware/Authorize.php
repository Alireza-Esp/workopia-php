<?php 


namespace Framework\Middleware;

use Framework\Session;

class Authorize {
    public static function isAuthenticated(): bool {
        return Session::exists('user');
    }

    public static function handle($role): void {
        if ($role === "guest" && static::isAuthenticated()) {
            return redirect('/');
        } elseif ($role === "auth" && !static::isAuthenticated()) {
            return redirect('/auth/login');
        }
    }
}