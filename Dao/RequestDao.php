<?php


namespace Icc\Dao;


use Icc\DBConnector;
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
        $this -> connection = new DBConnector();
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
            $formatString = sprintf("INSERT INTO request(id, employee_id, building, auditorium, reason, date, status) VALUES (DEFAULT, %d, '%s', '%s', '%s', '%s', %d);",
                $object -> getEmployeeId(),
                $object -> getBuilding(),
                $object -> getAuditorium(),
                $object -> getReason(),
                $object -> getDate(),
                $object -> getStatus());
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
                     status = %d WHERE id=%d",
                $object -> getEmployeeId(), $object -> getBuilding(), $object -> getAuditorium(),
                $object -> getReason(), $object -> getDate(), $object -> getStatus(), $object -> getId());
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
        return new Request($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4],
            $fetchedRow[5],
            $fetchedRow[6]);
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
}