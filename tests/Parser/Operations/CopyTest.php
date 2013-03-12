<?php

namespace FineDiffTests\Parser\Operations;

use PHPUnit_Framework_TestCase;
use cogpowered\FineDiff\Parser\Operations\Copy;

class OpcodesTest extends PHPUnit_Framework_TestCase
{
    public function testGetFromLen()
    {
        $copy = new Copy(10);
        $this->assertEquals($copy->getFromLen(), 10);
    }

    public function testGetToLen()
    {
        $copy = new Copy(342);
        $this->assertEquals($copy->getToLen(), 342);
    }

    public function testGetOpcode()
    {
        $copy = new Copy(1);
        $this->assertEquals($copy->getOpcode(), 'c');

        $copy = new Copy(24);
        $this->assertEquals($copy->getOpcode(), 'c24');
    }

    public function testIncrease()
    {
        $copy = new Copy(25);

        $this->assertEquals($copy->increase(5), 30);
        $this->assertEquals($copy->increase(10), 40);
        $this->assertEquals($copy->increase(64), 104);
    }
}
