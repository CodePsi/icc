<?php


namespace Icc\Controller;


use Icc\Dao\UserDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\User;
use Icc\Response\Response;

class UsersDataUpdateController
{
    public static function updatePassword(int $id, string $newPassword) {
        try {
            $dao = new UserDao();
            $user = $dao -> get($id);
//            $user = $dao->convertArrayToModels($dao -> where(array("login"), array("\"$login\""), array("=")))[0];
            $user -> setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
            $dao->update($user);
        } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
            Response::json(json_encode($e));
        }
    }
}