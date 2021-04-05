<?php


namespace Icc\Dao;


use Icc\Database\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\WriteOffAct;
use Icc\Utils\Utils;
use mysqli_result;

class WriteOffActDao extends AbstractDao implements Dao, ModelConverter
{
    private $connection;
    public function __construct()
    {
        $this -> connection = DBConnector::getInstance();
    }

    /**
     * Get item by id.
     *
     * @param int $id
     * @return object
     */
    function get(int $id): object
    {
        $item = $this -> connection -> execute_query("SELECT * FROM write_off_act WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            return new WriteOffAct(-1, '', '', -1);
        }

        return $this -> convertMysqlResultToModel($item);
    }

    /**
     * Save passed object.
     *
     * @param object $object
     * @return int return id of inserted item
     * @throws IncorrectObjectTypeException
     */
    function save(object $object): int
    {
        if ($object instanceof WriteOffAct) {
            $formatString = sprintf("INSERT INTO write_off_act(id, start_date, end_date, responsible_person_employee_id) VALUES (DEFAULT, '%s', '%s', %d);",
                $object -> getStartDate() -> format('Y-m-d'),
                $object -> getEndDate() -> format('Y-m-d'),
                $object -> getResponsiblePersonEmployeeId());
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not WriteOffAct");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    function delete(int $id): void
    {
        $stockItem = $this -> connection -> execute_query("DELETE FROM write_off_act WHERE id=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found write off act. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    function update(object $object): bool
    {
        if ($object instanceof WriteOffAct) {
            $formatString = sprintf("UPDATE write_off_act SET
                     start_date = '%s',
                     end_date = '%s',
                     responsible_person_employee_id = %d WHERE id=%d",
                $object -> getStartDate(), $object -> getEndDate(), $object -> getResponsiblePersonEmployeeId(), $object -> getId());
            return $this->connection->execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not WriteOffAct");
    }

    /**
     * @return array
     */
    function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM write_off_act");
        return $result -> fetch_all();
    }

    /**
     * Convert {@link mysqli_result} to {@link WriteOffAct}
     *
     * @param mysqli_result $mysqliResult
     * @return object
     */
    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new WriteOffAct($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3]);
    }


    /**
     * @param array $fields
     * @param array $values
     * @param array $operators
     * @return array
     */
    function where(array $fields, array $values, array $operators): array
    {
        $stringAndClausesBuilder = $this->buildAndClauses($fields, $values, $operators);
        $result = $this -> connection -> execute_query("SELECT * FROM write_off_act WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertArrayToModels(array $array): array
    {
        $resultArray = array();
        foreach ($array as $value) {
            Utils::cleanArrayFromNull($value);
            array_push($resultArray, new WriteOffAct($value[0],
                $value[1],
                $value[2],
                $value[3]));
        }

        return $resultArray;
    }
}