<?php

namespace FineDiffTests\Granularity;

use FineDiffTests\TestCase;
use CogPowered\FineDiff\Delimiters;
use CogPowered\FineDiff\Granularity\Character;

class CharacterTest extends TestCase
{
    /**
     * @var \CogPowered\FineDiff\Granularity\Granularity
     */
    protected $character;
    
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
        Delimiters::WORD,
        Delimiters::CHARACTER,
    );

    public function setUp()
    {
        $this->character = new Character;
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

    public function testSetDelimiters()
    {
        $arr = array('one', 'two');
        $this->character->setDelimiters($arr);
        $this->assertEquals($this->character->getDelimiters(), $arr);
    }

    public function testCountable()
    {
        $this->assertCount(count($this->character), $this->delimiters);
    }

    public function testArrayAccess()
    {
        // Exists
        for ($i = 0; $i < count($this->delimiters) + 1; $i++) {

            if ($i !== count($this->delimiters)) {
                $this->assertTrue(isset($this->character[$i]));
            } else {
                $this->assertFalse(isset($this->character[$i]));
            }
        }

        // Get
        for ($i = 0; $i < count($this->delimiters) + 1; $i++) {

            if ($i !== count($this->delimiters)) {
                $this->assertEquals($this->character[$i], $this->delimiters[$i]);
            } else {
                $this->assertNull($this->character[$i]);
            }
        }

        // Set
        for ($i = 0; $i < count($this->delimiters) + 1; $i++) {

            $rand = mt_rand(0, 1000);

            $this->character[$i] = $rand;
            $this->assertEquals($this->character[$i], $rand);
        }

        $this->assertEquals(count($this->character), count($this->delimiters) + 1);

        // Unset
        unset($this->character[ count($this->delimiters) ]);
        $this->assertCount(count($this->character), $this->delimiters);
    }
}
