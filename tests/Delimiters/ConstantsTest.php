<?php

namespace FineDiffTests\Delimiters;

use PHPUnit_Framework_TestCase;
use cogpowered\FineDiff\Delimiters;

class ConstantsTest extends PHPUnit_Framework_TestCase
{
    public function testParagraphConstant()
    {
        $this->assertEquals(Delimiters::PARAGRAPH, "\n\r");
    }

    public function testSentenceConstant()
    {
        $this->assertEquals(Delimiters::SENTENCE, ".\n\r");
    }

    public function testWordConstant()
    {
        $this->assertEquals(Delimiters::WORD, " \t.\n\r");
    }

    public function testCharacterConstant()
    {
        $this->assertEquals(Delimiters::CHARACTER, "");
    }
}