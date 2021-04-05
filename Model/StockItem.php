<?php
namespace Icc\Model;

class StockItem implements \JsonSerializable
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
     * @var string
    */
    private $unit;
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
     * @param int $id
     * @param string $itemName
     * @param string $type
     * @param string $unit
     * @param float $amount
     * @param float $price
     * @param float $total
     * @param int $responsiblePerson
     * @param string $code
     */
    public function __construct(int $id, string $itemName, string $type, string $unit, float $amount, float $price, float $total, int $responsiblePerson, string $code)
    {
        $this->id = $id;
        $this->itemName = $itemName;
        $this->type = $type;
        $this->unit = $unit;
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
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
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
        return json_encode(array('id' => $this -> getId(), 'itemName' => $this -> getItemName(), 'type' => $this -> getType(), 'unit' => $this -> getUnit(), 'amount' => $this -> getAmount(), 'price' => $this -> getPrice(),
            'total' => $this -> getTotal(), 'responsible' => $this -> getResponsiblePerson(), 'code' => $this -> getCode()), JSON_FORCE_OBJECT);
    }


    public function jsonSerialize(): array
    {
        return array('id' => intval($this -> getId()), 'itemName' => $this -> getItemName(), 'type' => $this -> getType(), 'unit' => $this -> getUnit(), 'amount' => $this -> getAmount(), 'price' => number_format(floatval($this -> getPrice()), 2),
            'total' => number_format(floatval($this -> getTotal()), 2), 'responsiblePerson' => intval($this -> getResponsiblePerson()), 'code' => $this -> getCode());
    }
}