<?php

namespace FineDiffTests\Parser;

use CogPowered\FineDiff\Parser\OperationCodes;
use FineDiffTests\TestCase;
use Mockery as m;

class OperationCodesTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\OperationCodesInterface', new OperationCodes);
    }

    public function testEmptyOperationCodes()
    {
        $operation_codes = new OperationCodes;
        $this->assertEmpty($operation_codes->getOperationCodes());
    }

    public function testSetOperationCodes()
    {
        $operation = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation->shouldReceive('getOperationCode')->once()->andReturn('testing');

        $operation_codes = new OperationCodes;
        $operation_codes->setOperationCodes(array($operation));

        $operation_codes = $operation_codes->getOperationCodes();
        $this->assertEquals($operation_codes[0], 'testing');
    }

    /**
     * @expectedException \CogPowered\FineDiff\Exceptions\OperationException
     */
    public function testNotOperation()
    {
        $operation_codes = new OperationCodes;
        $operation_codes->setOperationCodes(array('test'));
    }

    public function testGetOperationCodes()
    {
        $operation_one = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation_one->shouldReceive('getOperationCode')->andReturn('c5i');

        $operation_two = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation_two->shouldReceive('getOperationCode')->andReturn('2c6d');

        $operation_codes = new OperationCodes;
        $operation_codes->setOperationCodes(array($operation_one, $operation_two));

        $operation_codes = $operation_codes->getOperationCodes();

        $this->assertInternalType('array', $operation_codes);
        $this->assertEquals($operation_codes[0], 'c5i');
        $this->assertEquals($operation_codes[1], '2c6d');
    }

    public function testGenerate()
    {
        $operation_one = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation_one->shouldReceive('getOperationCode')->andReturn('c5i');

        $operation_two = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation_two->shouldReceive('getOperationCode')->andReturn('2c6d');

        $operation_codes = new OperationCodes;
        $operation_codes->setOperationCodes(array($operation_one, $operation_two));

        $this->assertEquals($operation_codes->generate(), 'c5i2c6d');
    }

    public function testToString()
    {
        $operation_one = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation_one->shouldReceive('getOperationCode')->andReturn('c5i');

        $operation_two = m::mock('CogPowered\FineDiff\Parser\Operations\Copy');
        $operation_two->shouldReceive('getOperationCode')->andReturn('2c6d');

        $operation_codes = new OperationCodes;
        $operation_codes->setOperationCodes(array($operation_one, $operation_two));

        $this->assertEquals((string)$operation_codes, 'c5i2c6d');
        $this->assertEquals((string)$operation_codes, $operation_codes->generate());
    }
}
