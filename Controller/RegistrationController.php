<?php


namespace Icc\Controller;


use Icc\Dao\UserDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\User;

class RegistrationController
{
    public static function registerUser(string $login, string $password, string $name) {
        $userDao = new UserDao();
        $user = new User(-1, $login, password_hash($password, PASSWORD_BCRYPT), $name);
        try {
            $userDao->save($user);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }
}