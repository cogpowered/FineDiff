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

namespace CogPowered\FineDiff\Parser\Operations;

/**
 * Generates the opcode for a delete operation.
 */
class Delete extends Operation
{
    /**
     * Set the initial length.
     *
     * @param int $len Length of string.
     */
    public function __construct($len)
    {
        $this->len = $len;
    }

    /**
     * @inheritdoc
     */
    public function getFromLen()
    {
        return $this->len;
    }

    /**
     * @inheritdoc
     */
    public function getToLen()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function getOperationCode()
    {
        if ($this->len === 1) {
            return Operation::DELETE;
        }

        return static::DELETE.$this->len;
    }
}
