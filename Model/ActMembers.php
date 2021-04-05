<?php


namespace Icc\Model;


class ActMembers implements \JsonSerializable
{
    private $id;
    private $employeeId;
    private $actPosition;
    private $appointmentDate;
    private $actType;

    /**
     * ActMembers constructor.
     * @param $id
     * @param $employeeId
     * @param $actPosition
     * @param $appointmentDate
     * @param $actType
     */
    public function __construct($id, $employeeId, $actPosition, $appointmentDate, $actType)
    {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->actPosition = $actPosition;
        $this->appointmentDate = $appointmentDate;
        $this->actType = $actType;
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
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @param $employeeId
     */
    public function setEmployeeId($employeeId): void
    {
        $this->$employeeId = $employeeId;
    }

    /**
     * @return mixed
     */
    public function getActPosition()
    {
        return $this->actPosition;
    }

    /**
     * @param mixed $actPosition
     */
    public function setActPosition($actPosition): void
    {
        $this->actPosition = $actPosition;
    }

    /**
     * @return mixed
     */
    public function getAppointmentDate()
    {
        return $this->appointmentDate;
    }

    /**
     * @param mixed $appointmentDate
     */
    public function setAppointmentDate($appointmentDate): void
    {
        $this->appointmentDate = $appointmentDate;
    }

    public function toJson(): string {
        return json_encode(array('id' => intval($this -> getId()), 'requestId' => $this -> getEmployeeId(), 'actPosition' => $this -> getActPosition(), 'appointment_date' => $this -> getAppointmentDate()), JSON_FORCE_OBJECT);
    }


    /**
     * @return mixed
     */
    public function getActType()
    {
        return $this->actType;
    }

    /**
     * @param mixed $actType
     */
    public function setActType($actType): void
    {
        $this->actType = $actType;
    }

    public function jsonSerialize(): array
    {
        return array('id' => intval($this -> getId()), 'employeeId' => intval($this -> getEmployeeId()),
            'actPosition' => $this -> getActPosition(), 'appointment_date' => $this -> getAppointmentDate(),
            'act_type' => $this -> getActType());

    }
}