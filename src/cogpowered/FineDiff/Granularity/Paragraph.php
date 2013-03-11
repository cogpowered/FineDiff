<?php

namespace cogpowered\FineDiff\Granularity;

use cogpowered\FineDiff\Delimiters;

class Paragraph extends Granularity
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
    );
}