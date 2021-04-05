<?php


namespace Icc\Dao;


use Icc\Database\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\InventoryNumber;
use Icc\Model\NotFoundItemException;
use Icc\Model\Request;
use Icc\Utils\Utils;
use mysqli_result;

/**
 *
 */
class InventoryNumberDao extends AbstractDao implements Dao, ModelConverter
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
        $item = $this->connection->execute_query("SELECT * FROM inventory_number WHERE id=$id");
        if (!$item || $item->num_rows === 0) {
            throw new NotFoundItemException("Not found inventory number. Error: " . DBConnector::$mysqli->error);
        }

        return $this->convertMysqlResultToModel($item);
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
        if ($object instanceof InventoryNumber) {
            $formatString = sprintf("INSERT INTO inventory_number(id, request_id, inventory_number) VALUES (DEFAULT, %d, '%s');",
                $object->getRequestId(),
                $object->getInventoryNumber());
            $this->connection->execute_query($formatString);
            return $this->connection->getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not InventoryNumber");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    function delete(int $id): void
    {
        $item = $this->connection->execute_query("DELETE FROM inventory_number WHERE id=$id");
        if (!$item || $item->num_rows === 0) {
            throw new NotFoundItemException("Not found inventory number. Error: " . DBConnector::$mysqli->error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    function update(object $object): bool
    {
        if ($object instanceof InventoryNumber) {
            $formatString = sprintf("UPDATE inventory_number SET
                     request_id = %d,
                     inventory_number = '%s'
                     WHERE id=%d",
                $object -> getRequestId(), $object -> getInventoryNumber(), $object->getId());
            return $this->connection->execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not InventoryNumber");
    }

    /**
     * @return array
     */
    function getAll(): array
    {
        $result = $this->connection->execute_query("SELECT * FROM inventory_number");
        return $result->fetch_all();
    }

    /**
     * Convert {@link mysqli_result} to {@link Request}
     *
     * @param mysqli_result $mysqliResult
     * @return object
     */
    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult->fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new InventoryNumber($fetchedRow[0],
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
        $result = $this->connection->execute_query("SELECT * FROM inventory_number WHERE $stringAndClausesBuilder;");
        return $result->fetch_all();
    }

    function convertArrayToModels(array $array): array
    {
        $resultArray = array();
        foreach ($array as $value) {
            Utils::cleanArrayFromNull($value);
            array_push($resultArray, new InventoryNumber($value[0],
                $value[1],
                $value[2]));
        }

        return $resultArray;
    }
}