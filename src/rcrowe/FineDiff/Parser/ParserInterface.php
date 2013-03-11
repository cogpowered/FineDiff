<?php

namespace rcrowe\FineDiff\Parser;

use rcrowe\FineDiff\Granularity\GranularityInterface;

interface ParserInterface
{
    public function __construct(GranularityInterface $granularity);
    public function getGranularity();
    public function setGranularity(GranularityInterface $granularity);
    public function parse($from_text, $to_text);
}