<?php


namespace Icc\Dao;


use Icc\Database\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\User;
use Icc\Utils\Utils;
use mysqli_result;

class UserDao extends AbstractDao implements Dao, ModelConverter
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
        $item = $this -> connection -> execute_query("SELECT * FROM users WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found user. Error: " . DBConnector::$mysqli -> error);
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
        if ($object instanceof User) {
            $formatString = sprintf("INSERT INTO users(id, login, password, name) VALUES (DEFAULT, '%s', '%s', '%s');",
                $object -> getLogin(),
                $object -> getPassword(),
                $object -> getName());
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not User");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    function delete(int $id): void
    {
        $item = $this -> connection -> execute_query("DELETE FROM users WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            throw new NotFoundItemException("Not found user. Error: " . DBConnector::$mysqli -> error);
        }
    }

    /**
     * @param object $object
     * @return bool
     * @throws IncorrectObjectTypeException
     */
    function update(object $object): bool
    {
        if ($object instanceof User) {
            $formatString = sprintf("UPDATE users SET
                     login = '%s',
                     password = '%s',
                     name = '%s'
                      WHERE id = %d",
                $object -> getLogin(), $object -> getPassword(), $object -> getName(), $object -> getId());
            return $this->connection->execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not User");
    }

    /**
     * @return array
     */
    function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM users");
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
        return new User($fetchedRow[0],
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
        $result = $this -> connection -> execute_query("SELECT * FROM users WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertArrayToModels(array $array): array
    {
        $resultArray = array();
        foreach ($array as $value) {
            Utils::cleanArrayFromNull($value);
            array_push($resultArray, new User($value[0],
                $value[1],
                $value[2],
                $value[3]));
        }

        return $resultArray;
    }


}