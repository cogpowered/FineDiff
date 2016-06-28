<?php

namespace FineDiffTests\Diff;

use PHPUnit_Framework_TestCase;
use bariew\FineDiff\Diff;

class DefaultsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->diff = new Diff;
    }

    public function testGetGranularity()
    {
        $this->assertTrue(is_a($this->diff->getGranularity(), 'bariew\FineDiff\Granularity\Character'));
        $this->assertTrue(is_a($this->diff->getGranularity(), 'bariew\FineDiff\Granularity\Granularity'));
        $this->assertTrue(is_a($this->diff->getGranularity(), 'bariew\FineDiff\Granularity\GranularityInterface'));
    }

    public function testGetRenderer()
    {
        $this->assertTrue(is_a($this->diff->getRenderer(), 'bariew\FineDiff\Render\Html'));
        $this->assertTrue(is_a($this->diff->getRenderer(), 'bariew\FineDiff\Render\Renderer'));
        $this->assertTrue(is_a($this->diff->getRenderer(), 'bariew\FineDiff\Render\RendererInterface'));
    }

    public function testGetParser()
    {
        $this->assertTrue(is_a($this->diff->getParser(), 'bariew\FineDiff\Parser\Parser'));
        $this->assertTrue(is_a($this->diff->getParser(), 'bariew\FineDiff\Parser\ParserInterface'));
    }
}