<?php


namespace Icc\Dao;


use Icc\DBConnector;
use Icc\Model\Employee;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Utils\Utils;
use mysqli_result;

class EmployeeDao extends AbstractDao implements Dao, ModelConverter
{
    private $connection;

    public function __construct()
    {
        $this -> connection = new DBConnector();
    }

    /**
     * @param int $id
     * @return object
     * @throws NotFoundItemException
     */
    public function get(int $id): object
    {
        $item = $this -> connection -> execute_query("SELECT * FROM employee WHERE id=$id");
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
        if ($object instanceof Employee) {
            $formatString = sprintf("INSERT INTO employee(id, name, surname, patronymic, status, contactNumber, position, responsible) 
                VALUES (DEFAULT, '%s', '%s', '%s', '%s', '%s', '%s', %d);",
                $object -> getName(),
                $object -> getSurname(),
                $object -> getPatronymic(),
                $object -> getStatus(),
                $object -> getContactNumber(),
                $object -> getPosition(),
                $object -> getResponsible());
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
        $stockItem = $this -> connection -> execute_query("DELETE FROM employee WHERE id=$id");
        if (!$stockItem || $stockItem -> num_rows === 0) {
            throw new NotFoundItemException("Not found employee. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    public function update(object $object): bool
    {
        if ($object instanceof Employee) {
            $formatString = sprintf("UPDATE employee SET
                     name = '%s',
                     surname = '%s',
                    patronymic = '%s',
                    status = '%s',
                    contactNumber = '%s', 
                    position = '%s', 
                    responsible = %d WHERE id=%d",
                $object -> getName(), $object -> getSurname(), $object -> getPatronymic(), $object -> getStatus(),
                $object -> getContactNumber(), $object -> getPosition(), $object -> getResponsible(), $object -> getId());
            return $this -> connection -> execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not Employee");
    }

    public function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM employee");
        return $result -> fetch_all();
    }

    public function where(array $fields, array $values, array $operators): array
    {
        $stringAndClausesBuilder = $this->buildAndClauses($fields, $values, $operators);
        $result = $this -> connection -> execute_query("SELECT * FROM employee WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new Employee($fetchedRow[0],
            $fetchedRow[1],
            $fetchedRow[2],
            $fetchedRow[3],
            $fetchedRow[4],
            $fetchedRow[5],
            $fetchedRow[6],
            $fetchedRow[7]);
    }
}