<?php


namespace Icc\Dao;


use Icc\Database\DBConnector;
use Icc\Model\ActOfInstallation;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Utils\Utils;
use mysqli_result;

class ActOfInstallationDao extends AbstractDao implements Dao, ModelConverter
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
        $item = $this -> connection -> execute_query("SELECT * FROM act_of_installation WHERE id=$id");
        if (!$item || $item -> num_rows === 0) {
            return new ActOfInstallation(-1, '', '', '', -1);
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
        if ($object instanceof ActOfInstallation) {
            $formatString = sprintf("INSERT INTO act_of_installation(id, start_date, end_date, month, responsible_person_employee_id) 
                VALUES (DEFAULT, '%s', '%s', '%s', %d);",
                $object -> getStartDate() -> format('Y-m-d'),
                $object -> getEndDate() -> format('Y-m-d'),
                $object -> getMonth(),
                $object -> getResponsiblePersonEmployeeId());
            $this -> connection -> execute_query($formatString);
            return $this -> connection -> getLastInsertedId();
        }

        throw new IncorrectObjectTypeException("Passed object's type is not ActOfInstallation");
    }

    /**
     * @param int $id
     * @throws NotFoundItemException
     */
    public function delete(int $id): void
    {
        $stockItem = $this -> connection -> execute_query("DELETE FROM act_of_installation WHERE id=$id");
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
        if ($object instanceof ActOfInstallation) {
            $formatString = sprintf("UPDATE act_of_installation SET
                     start_date = '%s',
                     end_date = '%s',
                     month = '%s',
                     responsible_person_employee_id = %d WHERE id=%d",
                $object -> getStartDate(), $object -> getEndDate(), $object -> getMonth(),
                $object -> getResponsiblePersonEmployeeId(), $object -> getId());
            return $this -> connection -> execute_query($formatString);
        }

        throw new IncorrectObjectTypeException("Passed object's type is not ActOfInstallation");
    }

    public function getAll(): array
    {
        $result = $this -> connection -> execute_query("SELECT * FROM act_of_installation");
        return $result -> fetch_all();
    }

    public function where(array $fields, array $values, array $operators): array
    {
        $stringAndClausesBuilder = $this->buildAndClauses($fields, $values, $operators);
        $result = $this -> connection -> execute_query("SELECT * FROM act_of_installation WHERE $stringAndClausesBuilder;");
        return $result -> fetch_all();
    }

    function convertMysqlResultToModel(mysqli_result $mysqliResult): object
    {
        $fetchedRow = $mysqliResult -> fetch_row();
        Utils::cleanArrayFromNull($fetchedRow);
        return new ActOfInstallation($fetchedRow[0],
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
            array_push($resultArray, new ActOfInstallation($value[0],
                $value[1],
                $value[2],
                $value[3],
                $value[4]));
        }

        return $resultArray;
    }

}