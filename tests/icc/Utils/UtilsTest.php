<?php

namespace Icc\tests\Utils;

use Icc\Utils\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    protected $array;
    protected function setUp(): void
    {
        $this->array = array("123", null);
    }

    public function testCleanArrayFromNull()
    {
        Utils::cleanArrayFromNull($this->array);
        $this -> assertNotNull($this->array[1]);
    }
}
