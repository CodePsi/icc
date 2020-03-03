<?php


namespace Icc\Model;


class InventoryNumber
{
    private $id;
    private $requestId;
    private $inventoryNumber;

    /**
     * InventoryNumber constructor.
     * @param $id
     * @param $requestId
     * @param $inventoryNumber
     */
    public function __construct($id, $requestId, $inventoryNumber)
    {
        $this->id = $id;
        $this->requestId = $requestId;
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


}