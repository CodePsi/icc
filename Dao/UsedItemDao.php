<?php


namespace Icc\Dao;


use Icc\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\UsedItem;
use Icc\Utils\Utils;
use mysqli_result;

class UsedItemDao extends AbstractDao implements Dao, ModelConverter
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
        $item = $this -> connection -> execute_query("SELECT * FROM used_item WHERE id=$id");
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
        if ($object instanceof UsedItem) {
            $formatString = sprintf("INSERT INTO used_item(id, request_id, item_id, amount, date, inventory_number) VALUES (DEFAULT, %d, %d, %d, '%s', %d);",
            $object -> getRequestId(),
            $object -> getItemId(),
            $object -> getAmount(),
            $object -> getDate(),
            $object -> getInventoryNumber());
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        //In case of the passed instance is not StockItem then
        //there will be thrown the Exception with information about it.
        throw new IncorrectObjectTypeException("Passed object's type is not UsedItem");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    function delete(int $id): void
    {
        $stockItem = $this -> connection -> execute_query("DELETE FROM used_item WHERE id=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found used item. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    function update(object $object): bool
    {
        if ($object instanceof UsedItem) {
            $formatString = sprintf("UPDATE used_item SET
                     request_id=%d,
                     item_id=%d,
                     amount=%d,
                     date='%s',
                     inventory_number=%d WHERE id=%d",
                $object -> getRequestId(), $object -> getItemId(), $object -> getAmount(),
                $object -> getDate(), $object -> getInventoryNumber(), $object -> getId());
            return $this->connection->execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not UsedItem");
    }

    /**
     * @return array
     */
    function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM used_item");
        return $result -> fetch_all();
    }

    /**
     * Convert {@link mysqli_result} to {@link StockItem}
     *
     * @param mysqli_result $mysqliResult
     * @return object
     */
    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new UsedItem($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4],
            $fetchedRow[5]);
    }

    function where(array $fields, array $values, array $operators): array
    {
        $stringAndClausesBuilder = $this->buildAndClauses($fields, $values, $operators);
        $result = $this -> connection -> execute_query("SELECT * FROM used_item WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }
}