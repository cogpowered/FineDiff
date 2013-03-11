<?php

namespace rcrowe\FineDiff\Granularity;

use rcrowe\FineDiff\Delimiters;

class Paragraph extends Granularity
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
    );
}