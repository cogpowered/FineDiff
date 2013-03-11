<?php

namespace rcrowe\FineDiff\Granularity;

abstract class Granularity implements GranularityInterface, \ArrayAccess, \Countable
{
    protected $delimiters = array();

    public function offsetExists($offset)
    {
        return isset($this->delimiters[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->delimiters[$offset]) ? $this->delimiters[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->delimiters[] = $value;
        } else {
            $this->delimiters[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->delimiters[$offset]);
    }

    public function count()
    {
        return count($this->delimiters);
    }

    public function getDelimiters()
    {
        return $this->delimiters;
    }

    public function setDelimiters(array $delimiters)
    {
        $this->delimiters = $delimiters;
    }
}