<?php

namespace FineDiffTests\Render\Text;

use FineDiffTests\TestCase;
use Mockery as m;
use CogPowered\FineDiff\Render\Text;

class ProcessTest extends TestCase
{
    /**
     * @var \CogPowered\FineDiff\Render\RendererInterface
     */
    protected $text;
    
    public function setUp()
    {
        $this->text = new Text;
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOperationCode()
    {
        $this->text->process('Hello worlds', 123);
    }

    public function testProcessWithString()
    {
        $html = $this->text->process('Hello worlds', 'c5i:2c6d');

        $this->assertEquals($html, 'Hello2 world');
    }

    public function testProcess()
    {
        $operation_codes = m::mock('CogPowered\FineDiff\Parser\OperationCodes');
        $operation_codes->shouldReceive('generate')->andReturn('c5i:2c6d')->once();

        $html = $this->text->process('Hello worlds', $operation_codes);

        $this->assertEquals($html, 'Hello2 world');
    }
}
