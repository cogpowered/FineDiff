<?php

namespace cogpowered\FineDiff\Granularity;

use cogpowered\FineDiff\Delimiters;

class Sentence extends Granularity
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
    );
}