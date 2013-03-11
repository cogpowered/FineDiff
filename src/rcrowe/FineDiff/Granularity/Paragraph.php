<?php

namespace rcrowe\FineDiff\Granularity;

use rcrowe\FineDiff\Delimiters;

class Paragraph implements GranularityInterface
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
    );
}