<?php

namespace FineDiffTests\Diff;

use CogPowered\FineDiff\Diff;
use FineDiffTests\TestCase;

class DefaultsTest extends TestCase
{
    /**
     * @var \CogPowered\FineDiff\Diff
     */
    protected $diff;
    
    public function setUp()
    {
        $this->diff = new Diff;
    }

    public function testGetGranularity()
    {
        $this->assertInstanceOf('CogPowered\FineDiff\Granularity\Character', $this->diff->getGranularity());
        $this->assertInstanceOf('CogPowered\FineDiff\Granularity\Granularity', $this->diff->getGranularity());
        $this->assertInstanceOf('CogPowered\FineDiff\Granularity\GranularityInterface', $this->diff->getGranularity());
    }

    public function testGetRenderer()
    {
        $this->assertInstanceOf('CogPowered\FineDiff\Render\Html', $this->diff->getRenderer());
        $this->assertInstanceOf('CogPowered\FineDiff\Render\Renderer', $this->diff->getRenderer());
        $this->assertInstanceOf('CogPowered\FineDiff\Render\RendererInterface', $this->diff->getRenderer());
    }

    public function testGetParser()
    {
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\Parser', $this->diff->getParser());
        $this->assertInstanceOf('CogPowered\FineDiff\Parser\ParserInterface', $this->diff->getParser());
    }
}
