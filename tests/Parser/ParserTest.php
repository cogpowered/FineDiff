<?php

namespace FineDiffTests\Parser;

use FineDiffTests\TestCase;
use Mockery as m;
use CogPowered\FineDiff\Granularity\Character;
use CogPowered\FineDiff\Parser\Parser;

class ParserTest extends TestCase
{
    /**
     * @var \CogPowered\FineDiff\Parser\ParserInterface
     */
    protected $parser;

    public function setUp()
    {
        $granularity  = new Character;
        $this->parser = new Parser($granularity);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\ParserInterface', $this->parser);
    }

    public function testDefaultOperationCodes()
    {
        $operation_codes = $this->parser->getOperationCodes();
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\OperationCodesInterface', $operation_codes);
    }

    public function testSetOperationCodes()
    {
        $operation_codes = m::mock('CogPowered\FineDiff\Parser\OperationCodes');
        $operation_codes->shouldReceive('foo')->andReturn('bar');
        $this->parser->setOperationCodes($operation_codes);

        $operation_codes = $this->parser->getOperationCodes();
        $this->assertEquals($operation_codes->foo(), 'bar');
    }

    /**
     * @expectedException \CogPowered\FineDiff\Exceptions\GranularityCountException
     */
    public function testParseBadGranularity()
    {
        $granularity = m::mock('CogPowered\FineDiff\Granularity\Character');
        $granularity->shouldReceive('count')->andReturn(0);
        $parser = new Parser($granularity);

        $parser->parse('hello world', 'hello2 worl');
    }

    public function testParseSetOperationCodes()
    {
        $operation_codes = m::mock('CogPowered\FineDiff\Parser\OperationCodes');
        $operation_codes->shouldReceive('setOperationCodes')->once();
        $this->parser->setOperationCodes($operation_codes);

        $this->parser->parse('Hello worlds', 'Hello2 world');
        
        $this->assertTrue(true);
    }
}
