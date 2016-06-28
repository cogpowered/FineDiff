<?php

/**
 * FINE granularity DIFF
 *
 * Computes a set of instructions to convert the content of
 * one string into another.
 *
 * Originally created by Raymond Hill (https://github.com/gorhill/PHP-FineDiff), brought up
 * to date by Cog Powered (https://github.com/bariew/FineDiff).
 *
 * @copyright Copyright 2011 (c) Raymond Hill (http://raymondhill.net/blog/?p=441)
 * @copyright Copyright 2013 (c) Robert Crowe (http://bariew.com)
 * @link https://github.com/bariew/FineDiff
 * @version 0.0.1
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace bariew\FineDiff\Parser;

use bariew\FineDiff\Granularity\GranularityInterface;

interface ParserInterface
{
    /**
     * Creates an instance.
     *
     * @param bariew\FineDiff\Granularity\GranularityInterface
     */
    public function __construct(GranularityInterface $granularity);

    /**
     * Granularity the parser is working with.
     *
     * Default is bariew\FineDiff\Granularity\Character.
     *
     * @see bariew\FineDiff\Granularity\Character
     * @see bariew\FineDiff\Granularity\Word
     * @see bariew\FineDiff\Granularity\Sentence
     * @see bariew\FineDiff\Granularity\Paragraph
     *
     * @return bariew\FineDiff\Granularity\GranularityInterface
     */
    public function getGranularity();

    /**
     * Set the granularity that the parser is working with.
     *
     * @see bariew\FineDiff\Granularity\Character
     * @see bariew\FineDiff\Granularity\Word
     * @see bariew\FineDiff\Granularity\Sentence
     * @see bariew\FineDiff\Granularity\Paragraph
     *
     * @param bariew\FineDiff\Granularity\GranularityInterface
     * @return void
     */
    public function setGranularity(GranularityInterface $granularity);

    /**
     * Get the opcodes object that is used to store all the opcodes.
     *
     * @return bariew\FineDiff\Parser\OpcodesInterface
     */
    public function getOpcodes();

    /**
     * Set the opcodes object used to store all the opcodes for this parse.
     *
     * @param bariew\FineDiff\Parser\OpcodesInterface $opcodes.
     * @return void
     */
    public function setOpcodes(OpcodesInterface $opcodes);

    /**
     * Generates the opcodes needed to transform one string to another.
     *
     * @param string $from_text
     * @param string $to_text
     * @throws bariew\FineDiff\Exceptions\GranularityCountException
     * @return bariew\FineDiff\Parser\OpcodesInterface
     */
    public function parse($from_text, $to_text);
}