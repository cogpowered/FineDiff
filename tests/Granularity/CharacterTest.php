<?php

namespace FineDiffTests\Granularity;

use PHPUnit_Framework_TestCase;
use cogpowered\FineDiff\Delimiters;
use cogpowered\FineDiff\Granularity\Character;

class CharacterTest extends PHPUnit_Framework_TestCase
{
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
        $this->assertTrue(is_a($this->character, 'cogpowered\FineDiff\Granularity\Granularity'));
        $this->assertTrue(is_a($this->character, 'cogpowered\FineDiff\Granularity\GranularityInterface'));
        $this->assertTrue(is_a($this->character, 'ArrayAccess'));
        $this->assertTrue(is_a($this->character, 'Countable'));
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
        $this->assertEquals(count($this->character), count($this->delimiters));
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

            $rand = rand(0, 1000);

            $this->character[$i] = $rand;
            $this->assertEquals($this->character[$i], $rand);
        }

        $this->assertEquals(count($this->character), count($this->delimiters) + 1);

        // Unset
        unset($this->character[ count($this->delimiters) ]);
        $this->assertEquals(count($this->character), count($this->delimiters));
    }
}