<?php

namespace FineDiffTests\Render\Html;

use PHPUnit_Framework_TestCase;
use cogpowered\FineDiff\Render\Html;

class CallbackTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->html = new Html;
    }

    public function testCopy()
    {
        $output = $this->html->callback('c', 'Hello', 0, 5);
        $this->assertEquals($output, 'Hello');

        $output = $this->html->callback('c', 'He&llo', 0, 100);
        $this->assertEquals($output, 'He&amp;llo');
    }

    public function testDelete()
    {
        $output = $this->html->callback('d', 'el', 0, 2);
        $this->assertEquals($output, '<del>el</del>');

        $output = $this->html->callback('d', "e&l", 0, 100);
        $this->assertEquals($output, '<del>e&amp;l</del>');
    }

    public function testInsert()
    {
        $output = $this->html->callback('i', 'monkey', 0, 6);
        $this->assertEquals($output, '<ins>monkey</ins>');

        $output = $this->html->callback('i', "mon&key", 0, 100);
        $this->assertEquals($output, '<ins>mon&amp;key</ins>');
    }
}