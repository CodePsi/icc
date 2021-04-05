<?php


namespace Icc\Secure;


use Exception;
use Firebase\JWT\JWT;
use Euro\Model\NotFoundItemException;

class JWTSecurity
{
    public static function getJWTTokenData() {

    }

    public static function validateToken() {
        $token = $_COOKIE['jwt'];
//        if (empty($token)) $token = $_SESSION['jwt_key'];
        $secretKey = "8351763f96c6f9ab9535f834d0518c6b";
        if (empty($token)) {
            http_response_code(401);
            return json_encode(array("message" => "The token is invalid"));
        }
        try {
            $jwtData = JWT::decode($token, $secretKey, array("HS256"));
        } catch (Exception $e) {
            http_response_code(401);
            return json_encode(array("message" => "The token is invalid"));
        }
//        if (!isset($jwtData)) {
//            echo json_encode(array("message" => "The token is invalid"));
//            return false;
//        }

//        if ($jwtData -> exp < time()) {
//            http_response_code(401);
//            return json_encode(array("message" => "The token has been expiring!"));
//            return false;
//        }
        $userDao = new UserDao();
        try {
            $user = $userDao->get($jwtData -> data -> id);
            if ($user -> getLogin() !== $jwtData -> data -> login
            || $user -> getPassword() !== $jwtData -> data -> password
            || $user -> getName() !== $jwtData -> data -> name) {
                http_response_code(401);
                return json_encode(array("message" => "The user is invalid"));
            }


        } catch (NotFoundItemException $e) {
            http_response_code(401);
            return json_encode(array("message" => "The user does not exist"));
        }

        return true;
    }

    public static function checkJWTToken()
    {

    }


}