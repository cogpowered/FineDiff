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

use CogPowered\FineDiff\Exceptions\OperationException;

/**
 * Holds all the operation codes returned by the parser.
 */
class OperationCodes implements OperationCodesInterface
{
    /**
     * @var string[] Individual operation codes.
     */
    protected $operation_codes = array();

    /**
     * @inheritdoc
     */
    public function getOperationCodes()
    {
        return $this->operation_codes;
    }

    /**
     * @inheritdoc
     */
    public function setOperationCodes(array $operation_codes)
    {
        $this->operation_codes = array();

        // Ensure that all elements of the array
        // are of the correct type
        /** @var \CogPowered\FineDiff\Parser\Operations\OperationInterface $operation_code */
        foreach ($operation_codes as $operation_code) {
            if (!is_a($operation_code, 'CogPowered\FineDiff\Parser\Operations\OperationInterface')) {
                throw new OperationException('Invalid operation code object');
            }

            $this->operation_codes[] = $operation_code->getOperationCode();
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        return implode('', $this->operation_codes);
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->generate();
    }
}
