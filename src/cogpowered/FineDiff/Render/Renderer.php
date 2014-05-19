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

namespace cogpowered\FineDiff\Render;

use cogpowered\FineDiff\Parser\OpcodesInterface;
use InvalidArgumentException;

abstract class Renderer implements RendererInterface
{
    /**
     * Covert text based on the provided opcodes.
     *
     * @param string                                              $from_text
     * @param string|\cogpowered\FineDiff\Parser\OpcodesInterface $opcodes
     *
     * @return string
     */
    public function process($from_text, $opcodes)
    {
        // Validate opcodes
        if (!is_string($opcodes) && !($opcodes instanceof OpcodesInterface)) {
            throw new InvalidArgumentException('Invalid opcodes type');
        } else {
            $opcodes = ($opcodes instanceof OpcodesInterface) ? $opcodes->generate() : $opcodes;
        }

        // Holds the generated string that is returned
        $output = '';

        $opcodes_len    = strlen($opcodes);
        $from_offset    = 0;
        $opcodes_offset = 0;

        while ($opcodes_offset < $opcodes_len) {

            $opcode = substr($opcodes, $opcodes_offset, 1);
            $opcodes_offset++;
            $n = intval(substr($opcodes, $opcodes_offset));

            if ($n) {
                $opcodes_offset += strlen(strval($n));
            } else {
                $n = 1;
            }

            if ($opcode === 'c') {
                // copy n characters from source
                $data = $this->callback('c', $from_text, $from_offset, $n);
                $from_offset += $n;
            } else if ($opcode === 'd') {
                // delete n characters from source
                $data = $this->callback('d', $from_text, $from_offset, $n);
                $from_offset += $n;
            } else /* if ( $opcode === 'i' ) */ {
                // insert n characters from opcodes
                $data = $this->callback('i', $opcodes, $opcodes_offset + 1, $n);
                $opcodes_offset += 1 + $n;
            }

            $output .= $data;
        }

        return $output;
    }
}