<?php

namespace FineDiffTests\Parser;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use cogpowered\FineDiff\Parser\Opcodes;

class OpcodesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $operation_once = m::mock('cogpowered\FineDiff\Parser\Operations\Copy');
        $operation_once->shouldReceive('getOpcode')->andReturn('c5i');

        $operation_two = m::mock('cogpowered\FineDiff\Parser\Operations\Copy');
        $operation_two->shouldReceive('getOpcode')->andReturn('2c6d');

        $this->opcodes = new Opcodes(array($operation_once, $operation_two));
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @expectedException cogpowered\FineDiff\Exceptions\OperationException
     */
    public function testNotOperation()
    {
        new Opcodes(array(
            'test'
        ));
    }

    public function testGetOpcode()
    {
        $operation = m::mock('cogpowered\FineDiff\Parser\Operations\Copy');
        $operation->shouldReceive('getOpcode')->once();

        new Opcodes(array(
            $operation
        ));
    }

    public function testGetOpcodes()
    {
        $opcodes = $this->opcodes->getOpcodes();

        $this->assertTrue(is_array($opcodes));
        $this->assertEquals($opcodes[0], 'c5i');
        $this->assertEquals($opcodes[1], '2c6d');
    }

    public function testGenerate()
    {
        $this->assertEquals($this->opcodes->generate(), 'c5i2c6d');
    }

    public function testToString()
    {
        ob_start();
        echo $this->opcodes;
        $output = ob_get_clean();

        $this->assertEquals($output, $this->opcodes->generate());
    }
}
