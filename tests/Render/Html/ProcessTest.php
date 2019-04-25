<?php

namespace FineDiffTests\Render\Html;

use FineDiffTests\TestCase;
use Mockery as m;
use CogPowered\FineDiff\Render\Html;

class ProcessTest extends TestCase
{
    /**
     * @var \CogPowered\FineDiff\Render\RendererInterface
     */
    protected $html;
    
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
        $operation_codes = m::mock('CogPowered\FineDiff\Parser\OperationCodes');
        $operation_codes->shouldReceive('generate')->andReturn('c5i:2c6d')->once();

        $html = $this->html->process('Hello worlds', $operation_codes);

        $this->assertEquals($html, 'Hello<ins>2</ins> world<del>s</del>');
    }
}
