<?php

namespace Icc\Dao;
use Icc\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Utils\Utils;
use mysqli_result;
use \Icc\Model\StockItem;

require __DIR__ . "/../../vendor/autoload.php";

class StockItemDao implements Dao, ModelConverter
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
        $stockItem = $this -> connection -> execute_query("SELECT * FROM stock_item WHERE id=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found stock item. Error: " . DBConnector::$mysqli -> error);
        }

        return $this -> convertMysqlResultToModel($stockItem);
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
        if ($object instanceof StockItem) {
            $formatString = sprintf("INSERT INTO stock_item (id, item_name, type, amount, price, total, responsible_person_employee_id, code) VALUES (DEFAULT, '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
            $object -> getItemName(),
            $object -> getType(),
            $object -> getAmount(),
            $object -> getPrice(),
            $object -> getTotal(),
            $object -> getResponsiblePerson(),
            $object -> getCode());
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        //In case of the passed instance is not StockItem then
        //there will be thrown the Exception with information about it.
        throw new IncorrectObjectTypeException("Passed object's type is not StockItem");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    function delete(int $id): void
    {
        $stockItem = $this -> connection -> execute_query("DELETE FROM stock_item WHERE id=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found stock item. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    function update(object $object): bool
    {
        if ($object instanceof StockItem) {
            $formatString = sprintf("UPDATE stock_item SET 
                      item_name='%s',
                      type='%s',
                      amount=%s,
                      price=%s,
                      total=%s,
                      responsible_person_employee_id=%s,
                      code='%s' WHERE id=%d",
                $object -> getItemName(), $object -> getType(), $object -> getAmount(), $object -> getPrice(),
                $object -> getTotal(), $object -> getResponsiblePerson(), $object -> getCode(), $object -> getId());
            return $this->connection->execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not StockItem");
    }

    /**
     * @return array
     */
    function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM stock_item");
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
        if ($fetchedRow[6] === "NULL") $fetchedRow[6] = -1;
        return new StockItem($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4],
            $fetchedRow[5],
            $fetchedRow[6],
            $fetchedRow[7]);
    }

    function where(array $fields, array $values, array $operators): array
    {
        // TODO: Implement where() method.
    }
}