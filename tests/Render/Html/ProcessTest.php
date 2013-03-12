<?php

namespace FineDiffTests\Render\Html;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use cogpowered\FineDiff\Render\Html;

class ProcessTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->html = new Html;
    }

    public function tearDown()
    {
        m::close();
    }

    public function testProcess()
    {
        $opcodes = m::mock('cogpowered\FineDiff\Parser\Opcodes');
        $opcodes->shouldReceive('generate')->andReturn('c5i:2c6d')->once();

        $html = $this->html->process('Hello worlds', $opcodes);

        $this->assertEquals($html, 'Hello<ins>2</ins> world<del>s</del>');
    }
}