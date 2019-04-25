<?php

namespace FineDiffTests\Parser\Operations;

use FineDiffTests\TestCase;
use CogPowered\FineDiff\Parser\Operations\Copy;

class CopyTest extends TestCase
{
    public function testImplementsOperationInterface()
    {
        $replace = new Copy(10);
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\Operations\OperationInterface', $replace);
    }

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

    public function testGetOperationCode()
    {
        $copy = new Copy(1);
        $this->assertEquals($copy->getOperationCode(), 'c');

        $copy = new Copy(24);
        $this->assertEquals($copy->getOperationCode(), 'c24');
    }

    public function testIncrease()
    {
        $copy = new Copy(25);

        $this->assertEquals($copy->increase(5), 30);
        $this->assertEquals($copy->increase(10), 40);
        $this->assertEquals($copy->increase(64), 104);
    }
}
