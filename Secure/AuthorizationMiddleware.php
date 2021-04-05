<?php


namespace Icc\Secure;


class AuthorizationMiddleware
{
    public static function userAuthorized() {
        return isset($_COOKIE["PHPSESSID"]) && isset($_SESSION["jwt_key"]);
    }

//    public static function
}