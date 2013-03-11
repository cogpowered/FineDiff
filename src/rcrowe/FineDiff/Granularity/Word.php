<?php

namespace rcrowe\FineDiff\Granularity;

use rcrowe\FineDiff\Delimiters;

class Word implements GranularityInterface
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
        Delimiters::WORD,
    );
}