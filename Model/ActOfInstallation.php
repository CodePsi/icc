<?php


namespace Icc\Model;


use Icc\Dao\StockItemDao;

class ActOfInstallation implements \JsonSerializable
{
    private $id;
    private $startDate;
    private $endDate;
    private $month;
    private $responsiblePersonEmployeeId;

    /**
     * ActOfInstallation constructor.
     * @param $id
     * @param $startDate
     * @param $endDate
     * @param $month
     * @param $responsiblePersonEmployeeId
     */
    public function __construct($id, $startDate, $endDate, $month, $responsiblePersonEmployeeId)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->month = $month;
        $this->responsiblePersonEmployeeId = $responsiblePersonEmployeeId;
    }

    public static function getItemsForActOfInstallationTable(array $items): array
    {
        $dao = new StockItemDao();
        $itemsForTable = array();
        foreach ($items as $usedItem) {
            try {
                $stockItem = $dao->get($usedItem[2]);
                if (strcasecmp($stockItem -> getType(), "Росходний матеріал") === 0) {
                    array_push($itemsForTable,
                        array($stockItem->getItemName(), $stockItem->getUnit(),
                            $usedItem[3], $stockItem->getPrice(), $stockItem->getPrice() * $usedItem[3],
                            'Встановлено в ' . $usedItem[5]));
                }
            } catch (NotFoundItemException $e) {
                echo $e;
            }
        }

        return $itemsForTable;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getResponsiblePersonEmployeeId()
    {
        return $this->responsiblePersonEmployeeId;
    }

    /**
     * @param mixed $responsiblePersonEmployeeId
     */
    public function setResponsiblePersonEmployeeId($responsiblePersonEmployeeId): void
    {
        $this->responsiblePersonEmployeeId = $responsiblePersonEmployeeId;
    }

    public function toJson() {
        return json_encode(array('id' => $this -> getId(), 'startDate' => $this -> getStartDate(), 'endDate' => $this->getEndDate(), 'month' => $this -> getMonth()));
    }

    public function jsonSerialize(): array
    {
        return array('id' => intval($this -> getId()), 'startDate' => $this -> getStartDate(),
            'endDate' => $this->getEndDate(), 'month' => $this -> getMonth(), 'responsiblePerson' => intval($this -> getResponsiblePersonEmployeeId()));
    }
}