<?php


namespace Icc\Model;


use Icc\Dao\StockItemDao;

class WriteOffAct implements \JsonSerializable
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $startDate;
    /**
     * @var
     */
    private $endDate;

    private $responsiblePersonEmployeeId;

    /**
     * WriteOffActDao constructor.
     * @param $id
     * @param $startDate
     * @param $endDate
     * @param $responsiblePersonEmployeeId
     */
    public function __construct($id, $startDate, $endDate, $responsiblePersonEmployeeId)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->responsiblePersonEmployeeId = $responsiblePersonEmployeeId;
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

    public static function getItemsForWriteOffActTable(array $usedItems) {
        $dao = new StockItemDao();
        $itemsForTable = array();
        foreach ($usedItems as $usedItem) {
            try {
                $stockItem = $dao->get($usedItem[2]);
                array_push($itemsForTable,
                    array($stockItem -> getItemName(), $stockItem -> getUnit(),
                        $usedItem[3], $stockItem -> getPrice(), $stockItem -> getPrice() * $usedItem[3],
                        'Встановлено в ' . $usedItem[5]));
            } catch (NotFoundItemException $e) {
                echo $e;
            }
        }

        return $itemsForTable;
    }

    /**
     * @return mixed
     */
    public function getResponsiblePersonEmployeeId()
    {
        return $this->responsiblePersonEmployeeId;
    }

    /**
     * @param int $responsiblePersonEmployeeId
     */
    public function setResponsiblePersonEmployeeId(int $responsiblePersonEmployeeId): void
    {
        $this->responsiblePersonEmployeeId = $responsiblePersonEmployeeId;
    }

    public function toJson() {
        return json_encode(array('id' => $this -> getId(), 'startDate' => $this -> getStartDate(), 'endDate' => $this->getEndDate()));
    }

    public function jsonSerialize(): array
    {
        return array('id' => intval($this -> getId()), 'startDate' => $this -> getStartDate(),
            'endDate' => $this->getEndDate(), 'responsiblePerson' => intval($this -> getResponsiblePersonEmployeeId()));
    }
}