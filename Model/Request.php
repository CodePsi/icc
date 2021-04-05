<?php


namespace Icc\Model;


class Request implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $employeeId;
    /**
     * @var string
     */
    private $building;
    /**
     * @var string
     */
    private $auditorium;
    /**
     * @var string
     */
    private $reason;
    /**
     * @var string
     */
    private $date;
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $technicalTicketNeeded;

    /**
     * Request constructor.
     * @param int $id
     * @param int $employeeId
     * @param string $building
     * @param string $auditorium
     * @param string $reason
     * @param string $date
     * @param int $status
     * @param int $technicalTicketNeeded
     */
    public function __construct(int $id, int $employeeId, string $building, string $auditorium, string $reason, string $date, int $status, int $technicalTicketNeeded)
    {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->building = $building;
        $this->auditorium = $auditorium;
        $this->reason = $reason;
        $this->date = $date;
        $this->status = $status;
        $this->technicalTicketNeeded = $technicalTicketNeeded;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    /**
     * @param int $employeeId
     */
    public function setEmployeeId(int $employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return string
     */
    public function getBuilding(): string
    {
        return $this->building;
    }

    /**
     * @param string $building
     */
    public function setBuilding(string $building): void
    {
        $this->building = $building;
    }

    /**
     * @return string
     */
    public function getAuditorium(): string
    {
        return $this->auditorium;
    }

    /**
     * @param string $auditorium
     */
    public function setAuditorium(string $auditorium): void
    {
        $this->auditorium = $auditorium;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getTechnicalTicketNeeded(): int
    {
        return $this->technicalTicketNeeded;
    }

    /**
     * @param int $technicalTicketNeeded
     */
    public function setTechnicalTicketNeeded(int $technicalTicketNeeded): void
    {
        $this->technicalTicketNeeded = $technicalTicketNeeded;
    }


    public function toJson(): string {
        return json_encode(array('id' => intval($this -> getId()), 'employeeId' => $this -> getEmployeeId(), 'building' => $this -> getBuilding(), 'auditorium' => $this -> getAuditorium(),
            'reason' => $this -> getReason(), 'date' => $this -> getDate(), 'status' => $this -> getStatus()), JSON_FORCE_OBJECT);
    }

    public function jsonSerialize(): array
    {
        return array('id' => $this -> getId(), 'employeeId' => $this -> getEmployeeId(), 'building' => $this -> getBuilding(), 'auditorium' => $this -> getAuditorium(),
            'reason' => $this -> getReason(), 'date' => $this -> getDate(), 'status' => boolval($this -> getStatus()), 'technicalTicketNeeded' => boolval($this -> getTechnicalTicketNeeded()));
    }
}