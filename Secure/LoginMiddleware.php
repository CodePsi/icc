<?php


namespace Icc\Secure;


use Exception;
use Firebase\JWT\JWT;
use Icc\Logger\Logger;

class LoginMiddleware
{
    public static function loggerConnected() {
        if (!isset($_SESSION['login'])) {
            session_start();
            try {
                $jwt = JWT::decode($_COOKIE['jwt'], '8351763f96c6f9ab9535f834d0518c6b', array('HS256'));
                $_SESSION['login'] = $jwt -> data -> login;
                $_SESSION['name'] = $jwt -> data -> name;
            } catch (Exception $e) {
                echo $e;
            }
        }
        Logger::getInstance($_SESSION['login'], $_SESSION['name']);
    }
}