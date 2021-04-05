<?php


namespace Icc\Controller;


use Firebase\JWT\JWT;
use Icc\Dao\UserDao;

class LoginController
{

    public static function login($login, $password)
    {
        $dao = new UserDao();
        $user = $dao -> where(array('login'), array("'$login'"), array('='));
        if (password_verify($password, $user[0][2])) {
            $secret_key = "8351763f96c6f9ab9535f834d0518c6b";
            $issuer_claim = "localhost/icc/login"; // this can be the servername
            $audience_claim = "localhost/icc/login";
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim; //not before in seconds
            $expire_claim = $issuedat_claim + 86400; // expire time in seconds (1 day)
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $user[0][0],
                    "login" => $user[0][1],
                    "password" => $user[0][2],
                    "name" => $user[0][3]
                ));

            http_response_code(200);

            $jwt = JWT::encode($token, $secret_key);

//            session_start();
            $_SESSION['jwt_key'] = $jwt;
            echo json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "token_type" => "bearer",
                    "expireAt" => $expire_claim
                ));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Login failed.", "password" => $password));
        }
//        JWT::encode();
    }
}