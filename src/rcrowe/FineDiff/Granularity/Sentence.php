<?php

namespace rcrowe\FineDiff\Granularity;

use rcrowe\FineDiff\Delimiters;

class Sentence implements GranularityInterface
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
    );
}