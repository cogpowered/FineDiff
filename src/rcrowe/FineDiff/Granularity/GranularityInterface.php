<?php

namespace rcrowe\FineDiff\Granularity;

interface GranularityInterface
{
    public function offsetExists($offset);
    public function offsetGet($offset);
    public function offsetSet($offset, $value);
    public function offsetUnset($offset);

    public function getDelimiters();
    public function setDelimiters(array $delimiters);
}
