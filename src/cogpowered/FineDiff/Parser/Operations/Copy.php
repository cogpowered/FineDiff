<?php

/**
* FINE granularity DIFF
*
* Computes a set of instructions to convert the content of
* one string into another.
*
* Originally created by Raymond Hill (github.com/gorhill/PHP-FineDiff), brought up
* to date by Cog Powered (github.com/cogpowered/FineDiff).
*
* @copyright Copyright 2011 (c) Raymond Hill (http://raymondhill.net/blog/?p=441)
* @copyright Copyright 2013 (c) Robert Crowe (http://cogpowered.com)
* @link https://github.com/cogpowered/FineDiff
* @version 0.0.1
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

namespace cogpowered\FineDiff\Parser\Operations;

class Copy implements OperationInterface
{
    public function __construct($len)
    {
        $this->len = $len;
    }

    public function getFromLen()
    {
        return $this->len;
    }

    public function getToLen()
    {
        return $this->len;
    }

    public function getOpcode()
    {
        if ($this->len === 1) {
            return 'c';
        }

        return "c{$this->len}";
    }

    public function increase($size)
    {
        return $this->len += $size;
    }
}