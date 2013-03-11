<?php

namespace cogpowered\FineDiff\Granularity;

use cogpowered\FineDiff\Delimiters;

class Character extends Granularity
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
        Delimiters::WORD,
        Delimiters::CHARACTER,
    );
}