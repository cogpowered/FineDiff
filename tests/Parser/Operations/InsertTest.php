<?php

namespace FineDiffTests\Parser\Operations;

use FineDiffTests\TestCase;
use CogPowered\FineDiff\Parser\Operations\Insert;

class InsertTest extends TestCase
{
    public function testImplementsOperationInterface()
    {
        $replace = new Insert('hello world');
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\Operations\OperationInterface', $replace);
    }

    public function testGetFromLen()
    {
        $insert = new Insert('hello world');
        $this->assertEquals($insert->getFromLen(), 0);
    }

    public function testGetToLen()
    {
        $insert = new Insert('hello world');
        $this->assertEquals($insert->getToLen(), 11);
    }

    public function testGetText()
    {
        $insert = new Insert('foobar');
        $this->assertEquals($insert->getText(), 'foobar');
    }

    public function testGetOperationCode()
    {
        $insert = new Insert('C');
        $this->assertEquals($insert->getOperationCode(), 'i:C');

        $insert = new Insert('blue');
        $this->assertEquals($insert->getOperationCode(), 'i4:blue');
    }
}
