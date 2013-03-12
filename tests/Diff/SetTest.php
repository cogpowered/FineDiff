<?php

namespace FineDiffTests\Diff;

use PHPUnit_Framework_TestCase;
use Mockery as m;
use cogpowered\FineDiff\Diff;

class SetTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->diff = new Diff;
    }

    public function tearDown()
    {
        m::close();
    }

    public function testSetParser()
    {
        $this->assertFalse( method_exists($this->diff->getParser(), 'fooBar') );

        $parser = m::mock('cogpowered\FineDiff\Parser\Parser');
        $parser->shouldReceive('fooBar')->once();

        $this->diff->setParser($parser);
        $parser = $this->diff->getParser();

        $parser->fooBar();
    }

    public function testSetRenderer()
    {
        $this->assertFalse( method_exists($this->diff->getRenderer(), 'fooBar') );

        $html = m::mock('cogpowered\FineDiff\Render\Html');
        $html->shouldReceive('fooBar')->once();

        $this->diff->setRenderer($html);
        $html = $this->diff->getRenderer();

        $html->fooBar();
    }

    public function testSetGranularity()
    {
        $this->assertFalse( method_exists($this->diff->getGranularity(), 'fooBar') );

        $granularity = m::mock('cogpowered\FineDiff\Granularity\Word');
        $granularity->shouldReceive('fooBar')->once();

        $parser = m::mock('cogpowered\FineDiff\Parser\Parser');
        $parser->shouldReceive('setGranularity')->with($granularity)->once();
        $parser->shouldReceive('getGranularity')->andReturn($granularity)->once();

        $this->diff->setParser($parser);
        $this->diff->setGranularity($granularity);

        $granularity = $this->diff->getGranularity();
        $granularity->fooBar();
    }
}
