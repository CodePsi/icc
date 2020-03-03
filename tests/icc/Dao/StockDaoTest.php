<?php

namespace Icc\tests\icc\Dao;

use Icc\Dao\StockItemDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\StockItem;
use PHPUnit\Framework\TestCase;

class StockDaoTest extends TestCase
{
    protected $stockDao;
    protected function setUp(): void
    {
        $this -> stockDao = new StockItemDao();
    }

    public function testGet() {
        try {
            $this->assertNotNull($this->stockDao->get(1));
            $this -> assertNotNull($this -> stockDao -> get(2));
            $this -> assertNotNull($this -> stockDao -> get(3));
            $this -> assertNotNull($this -> stockDao -> get(5));
            $this -> stockDao -> get(-1);
            $this -> expectException(NotFoundItemException::class);
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public function testSave() {

        try {
            $this->assertNotEquals($this->stockDao->save($this->stockDao->get(16)), -1);
        } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
        }
    }

    public function testDelete() {
            $tempInsertedId = $this->stockDao->save($this->stockDao->get(16));
            $this -> stockDao -> delete($tempInsertedId);

            $this -> expectException(NotFoundItemException::class);
            $this -> stockDao -> get($tempInsertedId);

    }

    public function testUpdate() {
        try {
            $object = $this->stockDao->get(16);
            $oldValue = $object -> getType();
            $object -> setType("NewType");
            $this -> stockDao -> update($object);
            $object = $this -> stockDao -> get(16);
            $this -> assertEquals("NewType", $object -> getType());

            $object -> setType("NewType2");
            $this -> stockDao -> update($object);
            $object = $this -> stockDao -> get(16);
            $this -> assertEquals("NewType2", $object -> getType());

            $object -> setType($oldValue);
            $this -> stockDao -> update($object);
            $object = $this -> stockDao -> get(16);
            $this -> assertEquals($oldValue, $object -> getType());
        } catch (NotFoundItemException | IncorrectObjectTypeException $e) {
        }

    }

    public function testGetAll() {
        $value = $this -> stockDao -> getAll();
        $this -> assertNotNull($value);
        $this -> assertIsArray($value);
    }
}