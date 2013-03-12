<?php

namespace FineDiffTests\Diff;

use PHPUnit_Framework_TestCase;
use cogpowered\FineDiff\Diff;

class DefaultsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->diff = new Diff;
    }

    public function testGetGranularity()
    {
        $this->assertTrue(is_a($this->diff->getGranularity(), 'cogpowered\FineDiff\Granularity\Character'));
        $this->assertTrue(is_a($this->diff->getGranularity(), 'cogpowered\FineDiff\Granularity\Granularity'));
        $this->assertTrue(is_a($this->diff->getGranularity(), 'cogpowered\FineDiff\Granularity\GranularityInterface'));
    }

    public function testGetRenderer()
    {
        $this->assertTrue(is_a($this->diff->getRenderer(), 'cogpowered\FineDiff\Render\Html'));
        $this->assertTrue(is_a($this->diff->getRenderer(), 'cogpowered\FineDiff\Render\Renderer'));
        $this->assertTrue(is_a($this->diff->getRenderer(), 'cogpowered\FineDiff\Render\RendererInterface'));
    }

    public function testGetParser()
    {
        $this->assertTrue(is_a($this->diff->getParser(), 'cogpowered\FineDiff\Parser\Parser'));
        $this->assertTrue(is_a($this->diff->getParser(), 'cogpowered\FineDiff\Parser\ParserInterface'));
    }
}