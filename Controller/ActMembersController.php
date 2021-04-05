<?php


namespace Icc\Controller;


use Icc\Dao\ActMembersDao;
use Icc\DBConnector;
use Icc\Model\ActMembers;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Response\Response;

class ActMembersController
{
    public static function addNewActMember($employeeId, $actPosition, $actType) {
        $dao = new ActMembersDao();
        try {
            $entry = new ActMembers(-1, $employeeId, $actPosition, '1950-01-01', $actType);
            $dao -> save($entry);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getActMember($id)
    {
        try {
            $dao = new ActMembersDao();
            echo json_encode($dao -> get($id));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function getAllActMembers()
    {
        $dao = new ActMembersDao();
        Response::json(json_encode($dao->convertArrayToModels($dao -> getAll())));
    }

    public static function updateActMember($id, $employeeId, $actPosition, $date, $actType)
    {
        $dao = new ActMembersDao();
        try {
            if (empty($date)) {
                $previousEntry = $dao -> get($id);
                $entry = new ActMembers($id, $employeeId, $actPosition, $previousEntry -> getAppointmentDate(), $actType);
            } else {
                $entry = new ActMembers($id, $employeeId, $actPosition, $date, $actType);
            }
            $dao -> update($entry);
            echo "Success";
        } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function deleteActMember($id)
    {
        $dao = new ActMembersDao();
        try {
            $dao -> delete($id);
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function addDateToAllActMembers($date)
    {
        $dao = new ActMembersDao();
        $entries = $dao -> getAll();
        foreach ($entries as $entry) {
            $entry[3] = $date;
            try {
                $dao->update(new ActMembers($entry[0], $entry[1], $entry[2], $entry[3]));
                echo "Success";
            } catch (IncorrectObjectTypeException $e) {
                echo $e;
            }
        }
    }
}