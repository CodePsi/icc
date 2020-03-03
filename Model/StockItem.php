<?php
namespace Icc\Model;

class StockItem
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $itemName;
    /**
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var float
     */
    private $price;
    /**
     * @var float
     */
    private $total;
    /**
     * @var int
     */
    private $responsiblePerson;
    /**
     * @var string
     */
    private $code;

    /**
     * StockItem constructor.
     * @param $id
     * @param $itemName
     * @param $type
     * @param $amount
     * @param $price
     * @param $total
     * @param $responsiblePerson
     * @param $code
     */
    public function __construct(int $id, string $itemName, string $type, int $amount, float $price, float $total, int $responsiblePerson, string $code)
    {
        $this->id = $id;
        $this->itemName = $itemName;
        $this->type = $type;
        $this->amount = $amount;
        $this->price = $price;
        $this->total = $total;
        $this->responsiblePerson = $responsiblePerson;
        $this->code = $code;
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
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @param mixed $itemName
     */
    public function setItemName($itemName): void
    {
        $this->itemName = $itemName;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getResponsiblePerson()
    {
        return $this->responsiblePerson;
    }

    /**
     * @param mixed $responsiblePerson
     */
    public function setResponsiblePerson($responsiblePerson): void
    {
        $this->responsiblePerson = $responsiblePerson;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function toJson(): string {
        return json_encode(array($this -> getId(), $this -> getItemName(), $this -> getType(), $this -> getAmount(), $this -> getPrice(),
            $this -> getTotal(), $this -> getResponsiblePerson(), $this -> getCode()), JSON_FORCE_OBJECT);
    }




}