<?php

namespace FineDiffTests\Render\Text;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use cogpowered\FineDiff\Render\Text;

class ProcessTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->text = new Text;
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidOpcode()
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
        $opcodes = m::mock('cogpowered\FineDiff\Parser\Opcodes');
        $opcodes->shouldReceive('generate')->andReturn('c5i:2c6d')->once();

        $html = $this->text->process('Hello worlds', $opcodes);

        $this->assertEquals($html, 'Hello2 world');
    }
}