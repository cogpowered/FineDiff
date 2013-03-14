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

/**
 * Generates the opcode for a copy operation.
 */
class Insert implements OperationInterface
{
    /**
     * Sets the text that the operation is working with.
     *
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @inheritdoc
     */
    public function getFromLen()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function getToLen()
    {
        return strlen($this->text);
    }

    /**
     * @inheritdoc
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
        $to_len = strlen($this->text);

        if ( $to_len === 1 ) {
            return "i:{$this->text}";
        }

        return "i{$to_len}:{$this->text}";
    }
}