<?php

namespace rcrowe\FineDiff\Tests\Usage;

use rcrowe\FineDiff as FineDiff;

class Testing extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $from_text = 'Hello worlds';
        $to_text   = 'Hello world';

        $opcodes = FineDiff::getDiffOpcodes($from_text, $to_text);
        var_dump($opcodes);
        echo "\n";
        echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);
        echo "\n";
    }
}