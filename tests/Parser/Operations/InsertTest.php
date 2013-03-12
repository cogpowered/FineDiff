<?php

namespace FineDiffTests\Parser\Operations;

use PHPUnit_Framework_TestCase;
use cogpowered\FineDiff\Parser\Operations\Insert;

class InsertTest extends PHPUnit_Framework_TestCase
{
    public function testImplementsOperationInterface()
    {
        $replace = new Insert('hello world');
        $this->assertTrue(is_a($replace, 'cogpowered\FineDiff\Parser\Operations\OperationInterface'));
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

    public function testGetOpcode()
    {
        $insert = new Insert('C');
        $this->assertEquals($insert->getOpcode(), 'i:C');

        $insert = new Insert('blue');
        $this->assertEquals($insert->getOpcode(), 'i4:blue');
    }
}
