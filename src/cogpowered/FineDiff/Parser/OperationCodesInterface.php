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

namespace CogPowered\FineDiff\Parser;

interface OperationCodesInterface
{
    /**
     * Get the operation codes.
     *
     * @return array
     */
    public function getOperationCodes();

    /**
     * Set the operation codes for this parse.
     *
     * @param \CogPowered\FineDiff\Parser\Operations\OperationInterface[] $operation_codes Elements must be an instance of \CogPowered\FineDiff\Parser\Operations\OperationInterface.
     *
     * @return void
     * @throws \CogPowered\FineDiff\Exceptions\OperationException
     */
    public function setOperationCodes(array $operation_codes);

    /**
     * Return the operation codes in a format that can then be rendered.
     *
     * @return string
     */
    public function generate();

    /**
     * When object is cast to a string returns operation codes as string.
     *
     * @see OperationCodes::generate
     * @return string
     */
    public function __toString();
}
