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

namespace CogPowered\FineDiff\Render;

interface RendererInterface
{
    /**
     * Covert text based on the provided operation codes.
     *
     * @param string                                                     $from_text
     * @param string|\CogPowered\FineDiff\Parser\OperationCodesInterface $operation_codes
     *
     * @return string
     */
    public function process($from_text, $operation_codes);

    /**
     * @param string $opcode
     * @param string $from
     * @param int    $from_offset
     * @param int    $from_len
     *
     * @return string
     */
    public function callback($opcode, $from, $from_offset, $from_len);
}
