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

class Replace implements OperationInterface
{
    public function __construct($fromLen, $text)
    {
        $this->fromLen = $fromLen;
        $this->text    = $text;
    }

    public function getFromLen()
    {
        return $this->fromLen;
    }

    public function getToLen()
    {
        return strlen($this->text);
    }

    public function getText()
    {
        return $this->text;
    }

    public function getOpcode()
    {
        if ($this->fromLen === 1) {
            $del_opcode = 'd';
        } else {
            $del_opcode = "d{$this->fromLen}";
        }

        $to_len = strlen($this->text);

        if ($to_len === 1) {
            return "{$del_opcode}i:{$this->text}";
        }

        return "{$del_opcode}i{$to_len}:{$this->text}";
    }
}