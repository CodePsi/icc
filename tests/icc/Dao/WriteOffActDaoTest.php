<?php


namespace Icc\tests\icc\Dao;


use Icc\Dao\WriteOffActDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use PHPUnit\Framework\TestCase;

class WriteOffActDaoTest extends TestCase
{
    protected $dao;
    protected function setUp(): void
    {
        $this -> dao = new WriteOffActDao();
    }

    public function testGet() {
        try {
            $this->assertNotNull($this->dao->get(1));
            $this -> assertNotNull($this -> dao -> get(2));
            $this -> assertNotNull($this -> dao -> get(3));
            $this -> assertNotNull($this -> dao -> get(5));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public function testSave() {

        try {
            $this->assertNotEquals($this->dao->save($this->dao->get(10)), -1);
        } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
        }
    }

    public function testDelete() {
        $tempInsertedId = $this->dao->save($this->dao->get(10));
        $this -> dao -> delete($tempInsertedId);

        $this -> expectException(NotFoundItemException::class);
        $this -> dao -> get($tempInsertedId);

    }

    public function testUpdate() {
        try {
            $object = $this->dao->get(10);
            $oldValue = $object -> getStartDate();
            $object -> setStartDate("");
            $this -> dao -> update($object);
            $object = $this -> dao -> get(10);
            $this -> assertEquals(10, $object -> getStartDate());

            $object -> setStartDate(12);
            $this -> dao -> update($object);
            $object = $this -> dao -> get(10);
            $this -> assertEquals(12, $object -> getStartDate());

            $object -> setStartDate($oldValue);
            $this -> dao -> update($object);
            $object = $this -> dao -> get(10);
            $this -> assertEquals($oldValue, $object -> getStartDate());
        } catch (NotFoundItemException | IncorrectObjectTypeException $e) {
        }

    }

    public function testGetAll() {
        $value = $this -> dao -> getAll();
        $this -> assertNotNull($value);
        $this -> assertIsArray($value);
    }
}