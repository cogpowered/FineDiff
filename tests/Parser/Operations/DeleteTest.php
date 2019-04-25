<?php

namespace FineDiffTests\Parser\Operations;

use FineDiffTests\TestCase;
use CogPowered\FineDiff\Parser\Operations\Delete;

class DeleteTest extends TestCase
{
    public function testImplementsOperationInterface()
    {
        $replace = new Delete(10);
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\Operations\OperationInterface', $replace);
    }

    public function testGetFromLen()
    {
        $delete = new Delete(10);
        $this->assertEquals($delete->getFromLen(), 10);
    }

    public function testGetToLen()
    {
        $delete = new Delete(342);
        $this->assertEquals($delete->getToLen(), 0);
    }

    public function testGetOperationCode()
    {
        $delete = new Delete(1);
        $this->assertEquals($delete->getOperationCode(), 'd');

        $delete = new Delete(24);
        $this->assertEquals($delete->getOperationCode(), 'd24');
    }
}
