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

use CogPowered\FineDiff\Granularity\GranularityInterface;

interface ParserInterface
{
    /**
     * Creates an instance.
     *
     * @param \CogPowered\FineDiff\Granularity\GranularityInterface
     */
    public function __construct(GranularityInterface $granularity);

    /**
     * Granularity the parser is working with.
     *
     * Default is CogPowered\FineDiff\Granularity\Character.
     *
     * @see \CogPowered\FineDiff\Granularity\Character
     * @see \CogPowered\FineDiff\Granularity\Word
     * @see \CogPowered\FineDiff\Granularity\Sentence
     * @see \CogPowered\FineDiff\Granularity\Paragraph
     *
     * @return \CogPowered\FineDiff\Granularity\GranularityInterface
     */
    public function getGranularity();

    /**
     * Set the granularity that the parser is working with.
     *
     * @see \CogPowered\FineDiff\Granularity\Character
     * @see \CogPowered\FineDiff\Granularity\Word
     * @see \CogPowered\FineDiff\Granularity\Sentence
     * @see \CogPowered\FineDiff\Granularity\Paragraph
     *
     * @param \CogPowered\FineDiff\Granularity\GranularityInterface
     *
     * @return void
     */
    public function setGranularity(GranularityInterface $granularity);

    /**
     * Get the operation codes object that is used to store all the operation codes.
     *
     * @return \CogPowered\FineDiff\Parser\OperationCodesInterface
     */
    public function getOperationCodes();

    /**
     * Set the operation codes object used to store all the operation codes for this parse.
     *
     * @param \CogPowered\FineDiff\Parser\OperationCodesInterface $operation_codes .
     *
     * @return void
     */
    public function setOperationCodes(OperationCodesInterface $operation_codes);

    /**
     * Generates the operation codes needed to transform one string to another.
     *
     * @param string $from_text
     * @param string $to_text
     *
     * @return \CogPowered\FineDiff\Parser\OperationCodesInterface
     * @throws \CogPowered\FineDiff\Exceptions\GranularityCountException
     */
    public function parse($from_text, $to_text);
}
