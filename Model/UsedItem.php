<?php


namespace Icc\Model;


class UsedItem implements \JsonSerializable
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $requestId;
    /**
     * @var
     */
    private $itemId;
    /**
     * @var
     */
    private $amount;
    /**
     * @var
     */
    private $date;
    /**
     * @var
     */
    private $inventoryNumber;

    /**
     * UsedItem constructor.
     * @param $id
     * @param $requestId
     * @param $itemId
     * @param $amount
     * @param $date
     * @param $inventoryNumber
     */
    public function __construct($id, $requestId, $itemId, $amount, $date, $inventoryNumber)
    {
        $this->id = $id;
        $this->requestId = $requestId;
        $this->itemId = $itemId;
        $this->amount = $amount;
        $this->date = $date;
        $this->inventoryNumber = $inventoryNumber;
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
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId): void
    {
        $this->requestId = $requestId;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $itemId
     */
    public function setItemId($itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getInventoryNumber()
    {
        return $this->inventoryNumber;
    }

    /**
     * @param mixed $inventoryNumber
     */
    public function setInventoryNumber($inventoryNumber): void
    {
        $this->inventoryNumber = $inventoryNumber;
    }


    public function jsonSerialize()
    {
        return array('id' => intval($this -> getId()), 'requestId' => intval($this -> getRequestId()), 'itemId' => intval($this -> getItemId(),),
            'amount' => doubleval($this -> amount), 'date' => $this -> date, 'inventoryNumber' => $this -> inventoryNumber);
    }
}