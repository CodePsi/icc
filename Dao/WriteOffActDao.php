<?php


namespace Icc\Dao;


use Icc\DBConnector;
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
        $item = $this -> connection -> execute_query("SELECT * FROM write_off_act WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found item. Error: " . DBConnector::$mysqli -> error);
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
            $formatString = sprintf("INSERT INTO write_off_act(id, start_date, end_date) VALUES (DEFAULT, '%s', '%s');",
                $object -> getStartDate() -> format('Y-m-d'),
                $object -> getEndDate() -> format('Y-m-d'));
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
                     end_date = '%s' WHERE id=%d",
                $object -> getStartDate(), $object -> getEndDate(), $object -> getId());
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
            $fetchedRow[2]);
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
//        echo "SELECT * FROM write_off_act WHERE $stringAndClausesBuilder;";
        $result = $this -> connection -> execute_query("SELECT * FROM write_off_act WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }
}