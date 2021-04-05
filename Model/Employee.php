<?php


namespace Icc\Model;


class Employee implements \JsonSerializable
{
    private $id;
    private $name;
    private $surname;
    private $patronymic;
    private $status;
    private $contactNumber;
    private $position;
    private $responsible;

    /**
     * Employee constructor.
     * @param $id
     * @param $name
     * @param $surname
     * @param $patronymic
     * @param $status
     * @param $contactNumber
     * @param $position
     * @param $responsible
     */
    public function __construct($id, $name, $surname, $patronymic, $status, $contactNumber, $position, $responsible)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
        $this->status = $status;
        $this->contactNumber = $contactNumber;
        $this->position = $position;
        $this->responsible = $responsible;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param mixed $patronymic
     */
    public function setPatronymic($patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param mixed $contactNumber
     */
    public function setContactNumber($contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * @param mixed $responsible
     */
    public function setResponsible($responsible): void
    {
        $this->responsible = $responsible;
    }

    public function toJson(): string {
        return json_encode(array('id' => intval($this -> getId()), 'name' => $this -> getName(), 'surname' => $this -> getSurname(), 'patronymic' => $this -> getPatronymic(),
            'status' => $this -> getStatus(), 'contactNumber' => $this -> getContactNumber(), 'position' => $this -> getPosition(), 'responsible' => $this -> getResponsible()), JSON_FORCE_OBJECT);
    }


    public function jsonSerialize(): array
    {
        return array('id' => intval($this -> getId()), 'name' => $this -> getName(), 'surname' => $this -> getSurname(), 'patronymic' => $this -> getPatronymic(),
            'status' => $this -> getStatus(), 'contactNumber' => $this -> getContactNumber(), 'position' => $this -> getPosition(), 'responsible' => intval($this -> getResponsible()));
    }
}