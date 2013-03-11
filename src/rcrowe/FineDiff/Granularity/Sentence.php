<?php

namespace rcrowe\FineDiff\Granularity;

use rcrowe\FineDiff\Delimiters;

class Sentence extends Granularity
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
    );
}