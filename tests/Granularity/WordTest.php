<?php

namespace FineDiffTests\Granularity;

use FineDiffTests\TestCase;
use CogPowered\FineDiff\Delimiters;
use CogPowered\FineDiff\Granularity\Word;

class WordTest extends TestCase
{
    /**
     * @var \CogPowered\FineDiff\Granularity\Granularity
     */
    protected $character;

    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
        Delimiters::WORD,
    );

    public function setUp()
    {
        $this->character = new Word;
    }

    public function testExtendsAndImplements()
    {
        $this->assertInstanceOf('CogPowered\FineDiff\Granularity\Granularity', $this->character);
        $this->assertInstanceOf('CogPowered\FineDiff\Granularity\GranularityInterface', $this->character);
        $this->assertInstanceOf('ArrayAccess', $this->character);
        $this->assertInstanceOf('Countable', $this->character);
    }

    public function testGetDelimiters()
    {
        $this->assertEquals($this->character->getDelimiters(), $this->delimiters);
    }
}
