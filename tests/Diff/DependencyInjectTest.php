<?php

namespace FineDiffTests\Diff;

use FineDiffTests\TestCase;
use Mockery as m;
use CogPowered\FineDiff\Diff;

class DependencyInjectTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testGetGranularity()
    {
        $character = m::mock('CogPowered\FineDiff\Granularity\Character');

        $diff = new Diff($character);
        $granularity = $diff->getGranularity();

        $this->assertInstanceOf('CogPowered\FineDiff\Granularity\Character', $granularity);
    }

    public function testGetRenderer()
    {
        $html = m::mock('CogPowered\FineDiff\Render\Html');

        $diff = new Diff(null, $html);
        $renderer = $diff->getRenderer();

        $this->assertInstanceOf('CogPowered\FineDiff\Render\Html', $renderer);
    }

    public function testRender()
    {
        $operation_codes = m::mock('CogPowered\FineDiff\Parser\OperationCodes');
        $operation_codes->shouldReceive('generate')->andReturn('c12');

        $parser = m::mock('CogPowered\FineDiff\Parser\Parser');
        $parser->shouldReceive('parse')->andReturn($operation_codes);

        $html = m::mock('CogPowered\FineDiff\Render\Html');
        $html->shouldReceive('process')->with('hello', $operation_codes)->once();


        $diff = new Diff(null, $html, $parser);
        $diff->render('hello', 'hello2');
        
        $this->assertTrue(true);
    }

    public function testGetParser()
    {
        $parser = m::mock('CogPowered\FineDiff\Parser\Parser');

        $diff = new Diff(null, null, $parser);
        $parser = $diff->getParser();

        $this->assertInstanceOf('CogPowered\FineDiff\Parser\Parser', $parser);
    }

    public function testGetOperationCodes()
    {
        $parser = m::mock('CogPowered\FineDiff\Parser\Parser');
        $parser->shouldReceive('parse')->with('foobar', 'eggfooba')->once();

        $diff = new Diff(null, null, $parser);
        $diff->getOperationCodes('foobar', 'eggfooba');

        $this->assertTrue(true);
    }
}
