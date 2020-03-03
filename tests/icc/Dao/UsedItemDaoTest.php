<?php


namespace Icc\tests\icc\Dao;


use Icc\Dao\UsedItemDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use PHPUnit\Framework\TestCase;

class UsedItemDaoTest extends TestCase
{
    protected $dao;
    protected function setUp(): void
    {
        $this -> dao = new UsedItemDao();
    }

    public function testGet() {
        try {
            $this->assertNotNull($this->dao->get(1));
            $this -> assertNotNull($this -> dao -> get(2));
            $this -> assertNotNull($this -> dao -> get(3));
            $this -> assertNotNull($this -> dao -> get(5));
            $this -> dao -> get(-1);
            $this -> expectException(NotFoundItemException::class);
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
            $oldValue = $object -> getAmount();
            $object -> setAmount(10);
            $this -> dao -> update($object);
            $object = $this -> dao -> get(10);
            $this -> assertEquals(10, $object -> getAmount());

            $object -> setAmount(12);
            $this -> dao -> update($object);
            $object = $this -> dao -> get(10);
            $this -> assertEquals(12, $object -> getAmount());

            $object -> setAmount($oldValue);
            $this -> dao -> update($object);
            $object = $this -> dao -> get(10);
            $this -> assertEquals($oldValue, $object -> getAmount());
        } catch (NotFoundItemException | IncorrectObjectTypeException $e) {
        }

    }

    public function testGetAll() {
        $value = $this -> dao -> getAll();
        $this -> assertNotNull($value);
        $this -> assertIsArray($value);
    }
}