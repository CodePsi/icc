<?php


namespace Icc\Model;


use Icc\Dao\StockItemDao;

class WriteOffAct
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

    /**
     * WriteOffActDao constructor.
     * @param $id
     * @param $startDate
     * @param $endDate
     */
    public function __construct($id, $startDate, $endDate)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
                    array($stockItem -> getItemName(), $stockItem -> getType(),
                        $usedItem[3], $stockItem -> getPrice(), $stockItem -> getPrice() * $usedItem[3],
                        'Installed in ' . $stockItem -> getCode()));
            } catch (NotFoundItemException $e) {
                echo $e;
            }
        }

        return $itemsForTable;
    }


}