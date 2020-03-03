<?php


namespace Icc\Model;


class Request
{
    private $id;
    private $employeeId;
    private $building;
    private $auditorium;
    private $reason;
    private $date;
    private $status;

    /**
     * Request constructor.
     * @param $id
     * @param $employeeId
     * @param $building
     * @param $auditorium
     * @param $reason
     * @param $date
     * @param $status
     */
    public function __construct($id, $employeeId, $building, $auditorium, $reason, $date, $status)
    {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->building = $building;
        $this->auditorium = $auditorium;
        $this->reason = $reason;
        $this->date = $date;
        $this->status = $status;
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
     * @param mixed $employeeId
     */
    public function setEmployeeId($employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building): void
    {
        $this->building = $building;
    }

    /**
     * @return mixed
     */
    public function getAuditorium()
    {
        return $this->auditorium;
    }

    /**
     * @param mixed $auditorium
     */
    public function setAuditorium($auditorium): void
    {
        $this->auditorium = $auditorium;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason): void
    {
        $this->reason = $reason;
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

    public function toJson(): string {
        return json_encode(array('id' => intval($this -> getId()), 'employeeId' => $this -> getEmployeeId(), 'building' => $this -> getBuilding(), 'auditorium' => $this -> getAuditorium(),
            'reason' => $this -> getReason(), 'date' => $this -> getDate(), 'status' => $this -> getStatus()), JSON_FORCE_OBJECT);
    }




}