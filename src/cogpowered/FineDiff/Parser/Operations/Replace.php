<?php

/**
 * FINE granularity DIFF
 *
 * Computes a set of instructions to convert the content of
 * one string into another.
 *
 * Originally created by Raymond Hill (https://github.com/gorhill/PHP-FineDiff), brought up
 * to date by Cog Powered (https://github.com/cogpowered/FineDiff).
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
    /**
     * @param int $fromLen
     * @param string $text
     */
    public function __construct($fromLen, $text)
    {
        $this->fromLen = $fromLen;
        $this->text    = $text;
    }

    /**
     * @inheritdoc
     */
    public function getFromLen()
    {
        return $this->fromLen;
    }

    /**
     * @inheritdoc
     */
    public function getToLen()
    {
        return strlen($this->text);
    }

    /**
     * Get the text the operation is working with.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @inheritdoc
     */
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