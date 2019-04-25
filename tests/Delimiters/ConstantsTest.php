<?php

namespace FineDiffTests\Delimiters;

use CogPowered\FineDiff\Parser\Operations\Operation;
use CogPowered\FineDiff\Delimiters;
use FineDiffTests\TestCase;

class ConstantsTest extends TestCase
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
        $this->assertEquals(Delimiters::CHARACTER, '');
    }

    public function testCopyConstant()
    {
        $this->assertEquals(Operation::COPY, 'c');
    }

    public function testDeleteConstant()
    {
        $this->assertEquals(Operation::DELETE, 'd');
    }

    public function testInsertConstant()
    {
        $this->assertEquals(Operation::INSERT, 'i');
    }
}
