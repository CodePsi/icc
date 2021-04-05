<?php


namespace Icc\Dao;


use Icc\Database\DBConnector;
use Icc\Model\ActMembers;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Utils\Utils;
use mysqli_result;

class ActMembersDao extends AbstractDao implements Dao, ModelConverter
{
    private $connection;

    public function __construct()
    {
        $this -> connection = DBConnector::getInstance();
    }

    /**
     * @param int $id
     * @return object
     * @throws NotFoundItemException
     */
    public function get(int $id): object
    {
        $item = $this -> connection -> execute_query("SELECT * FROM act_members WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found item. Error: " . DBConnector::$mysqli -> error);
        }

        return $this -> convertMysqlResultToModel($item);
    }

    /**
     * @param object $object
     * @return int
     * @throws IncorrectObjectTypeException
     */
    public function save(object $object): int
    {
        if ($object instanceof ActMembers) {
            $formatString = sprintf("INSERT INTO act_members(id, employee_id, act_position, appointment_date, act_type) 
                VALUES (DEFAULT, %d, '%s', '%s', '%s');",
                $object -> getEmployeeId(),
                $object -> getActPosition(),
                $object -> getAppointmentDate(),
                $object -> getActType());
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not Employee");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    public function delete(int $id): void
    {
        $stockItem = $this -> connection -> execute_query("DELETE FROM act_members WHERE id=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found item. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    public function update(object $object): bool
    {
        if ($object instanceof ActMembers) {
            $formatString = sprintf("UPDATE act_members SET
                     employee_id = %d,
                     act_position = '%s',
                     appointment_date = '%s',
                     act_type = '%s' WHERE id=%d",
                $object -> getEmployeeId(), $object -> getActPosition(), $object -> getAppointmentDate(),
                $object -> getActType(), $object -> getId());
            return $this -> connection -> execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not ActMembers");
    }

    public function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM act_members");
        return $result -> fetch_all();
    }

    public function where(array $fields, array $values, array $operators): array
    {
        $stringAndClausesBuilder = $this->buildAndClauses($fields, $values, $operators);
        $result = $this -> connection -> execute_query("SELECT * FROM act_members WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new ActMembers($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4]);
    }

    function convertArrayToModels(array $array): array
    {
        $resultArray = array();
        foreach ($array as $value) {
            Utils::cleanArrayFromNull($value);
            array_push($resultArray, new ActMembers($value[0],
                $value[1],
                $value[2],
                $value[3],
                $value[4]));
        }

        return $resultArray;
    }

}