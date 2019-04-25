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

use CogPowered\FineDiff\Parser\OperationCodesInterface;
use CogPowered\FineDiff\Parser\Operations\Operation;
use InvalidArgumentException;

abstract class Renderer implements RendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($from_text, $operation_codes)
    {
        // Validate operation codes
        if (!is_string($operation_codes) && !($operation_codes instanceof OperationCodesInterface)) {
            throw new InvalidArgumentException('Invalid operation codes type');
        }

        $operation_codes = ($operation_codes instanceof OperationCodesInterface) ? $operation_codes->generate() : $operation_codes;


        // Holds the generated string that is returned
        $output = '';

        $operation_codes_len    = strlen($operation_codes);
        $from_offset    = 0;
        $operation_codes_offset = 0;

        while ($operation_codes_offset < $operation_codes_len) {

            $opcode = $operation_codes[$operation_codes_offset];
            $operation_codes_offset++;
            $n = (int)substr($operation_codes, $operation_codes_offset);

            if ($n) {
                $operation_codes_offset += strlen((string)$n);
            } else {
                $n = 1;
            }

            switch ($opcode) {
                case Operation::COPY:
                case Operation::DELETE:
                    $data = $this->callback($opcode, $from_text, $from_offset, $n);
                    $from_offset += $n;
                    break;
                case Operation::INSERT:
                    $data = $this->callback($opcode, $operation_codes, $operation_codes_offset + 1, $n);
                    $operation_codes_offset += 1 + $n;
                    break;
                default:
                    $data = '';
            }

            $output .= $data;
        }

        return $output;
    }
}
