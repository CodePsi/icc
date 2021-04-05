<?php


namespace Icc\Dao;


use Icc\Database\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\Request;
use Icc\Utils\Utils;
use mysqli_result;

class RequestDao extends AbstractDao implements Dao, ModelConverter
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
     * @throws NotFoundItemException
     */
    function get(int $id): object
    {
        $item = $this -> connection -> execute_query("SELECT * FROM request WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found request. Error: " . DBConnector::$mysqli -> error);
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
        if ($object instanceof Request) {

            $formatString = sprintf("INSERT INTO request(id, employee_id, building, auditorium, reason, date, status, technical_ticket_needed) VALUES (DEFAULT, %d, '%s', '%s', '%s', '%s', %d, %d);",
                $object -> getEmployeeId(),
                $object -> getBuilding(),
                $object -> getAuditorium(),
                DBConnector::getMysqli() -> real_escape_string($object -> getReason()),
                $object -> getDate(),
                $object -> getStatus(),
                $object -> getTechnicalTicketNeeded());
            var_dump($formatString);
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not Request");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    function delete(int $id): void
    {
        $item = $this -> connection -> execute_query("DELETE FROM request WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found request. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    function update(object $object): bool
    {
        if ($object instanceof Request) {
            $formatString = sprintf("UPDATE request SET
                     employee_id = %d,
                     building = '%s',
                     auditorium = '%s',
                     reason = '%s',
                     date = '%s',
                     status = %d,
                     technical_ticket_needed = %d WHERE id=%d",
                $object -> getEmployeeId(), $object -> getBuilding(), $object -> getAuditorium(),
                DBConnector::getMysqli() -> real_escape_string($object -> getReason()), $object -> getDate(),
                $object -> getStatus(), $object -> getTechnicalTicketNeeded(), $object -> getId());
            return $this->connection->execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not Request");
    }

    /**
     * @return array
     */
    function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM request");
        return $result -> fetch_all();
    }

    /**
     * Convert {@link mysqli_result} to {@link Request}
     *
     * @param mysqli_result $mysqliResult
     * @return object
     */
    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new Request(intval($fetchedRow[0]),
            intval($fetchedRow[1]),
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4],
            $fetchedRow[5],
            boolval($fetchedRow[6]),
            boolval($fetchedRow[7]));
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
        $result = $this -> connection -> execute_query("SELECT * FROM request WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertArrayToModels(array $array): array
    {
        $resultArray = array();
        foreach ($array as $value) {
            Utils::cleanArrayFromNull($value, '');
            array_push($resultArray, new Request($value[0],
                $value[1],
                $value[2],
                $value[3],
                $value[4],
                $value[5],
                $value[6],
                $value[7]));
        }

        return $resultArray;
    }
}