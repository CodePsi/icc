<?php


namespace Icc\Controller;


use Icc\Dao\EmployeeDao;
use Icc\Model\Employee;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Response\Response;
use Icc\Response\ResponseDataReceiver;
use function Icc\Response\json;
use function Icc\Response\queryParameter;

class EmployeeController
{
    public static function addNewEmployee($name, $surname, $patronymic, $status, $contactNumber, $position, $responsible) {
        $dao = new EmployeeDao();
        $entity = new Employee(-1, $name, $surname, $patronymic, $status, $contactNumber, $position, $responsible);
        try {
            $dao -> save($entity);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    } 

    public static function getAllEmployees() {
        $dao = new EmployeeDao();
        if (ResponseDataReceiver::isQueryParametersProvided()) {
            $queryParameters = ResponseDataReceiver::getQueryParameters();
            $fields = array();
            $values = array();
            foreach ($queryParameters as $parameter => $value) {
                array_push($fields, $parameter);
                array_push($values, $value);
            }
            echo json_encode($dao->convertArrayToModels($dao -> where($fields, $values, array('='))));
        } else {
            echo json_encode($dao->convertArrayToModels($dao->getAll()));
        }
    }

    public static function getEmployee($id)
    {
        $dao = new EmployeeDao();
        try {
            echo json_encode($dao -> get($id));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function updateEmployee($id, $name, $surname, $patronymic, $status, $contactNumber, $position, $responsible)
    {
        $dao = new EmployeeDao();
        $entity = new Employee($id, $name, $surname, $patronymic, $status, $contactNumber, $position, $responsible);
        try {
            $dao -> update($entity);
            echo "Success";
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function deleteEmployee($id)
    {
        $dao = new EmployeeDao();
        try {
            $dao -> delete($id);
            echo "Success";
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function getAllResponsible() {
        $dao = new EmployeeDao();
        return json_encode($dao -> where(array('responsible'), array(1), array('=')));
    }


}