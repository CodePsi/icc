<?php


namespace Icc\Controller;


use Icc\Dao\UserDao;
use Icc\Json\JSON;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\User;
use Icc\Response\Response;
use Icc\Response\ResponseDataReceiver;

class UsersController
{
    public static function getAllUsers() {
        $userDao = new UserDao();
        Response::json(json_encode($userDao -> getAll()));
    }

    public static function getUser($id)
    {
        try {
            $userDao = new UserDao();
            Response::json(json_encode($userDao->get($id)));
        } catch (NotFoundItemException $e) {
            Response::json(json_encode($e));
        }
    }

    public static function changeUser($id, $login, $name)
    {
        try {
            $userDao = new UserDao();
            $user = $userDao -> get($id);
            $user -> setLogin($login);
            $user -> setName($name);
            $userDao->update($user);
        } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
            Response::json(json_encode($e));
        }
    }

    public static function addNewUser($login, $password, $name)
    {
        $userDao = new UserDao();
        $user = new User(-1, $login, $password, $name);
        try {
            $userDao->save($user);
        } catch (IncorrectObjectTypeException $e) {
            Response::json(json_encode($e));
        }
    }


}