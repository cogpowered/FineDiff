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

namespace cogpowered\FineDiff;

/**
 * Used by classes implementing cogpowered\FineDiff\Granularity\GranularityInterface.
 *
 * Class is used more like an Enum type; the class can not be instantiated.
 */
abstract class Delimiters
{
    const PARAGRAPH = "\n\r";
    const SENTENCE  = ".\n\r";
    const WORD      = " \t.\n\r";
    const CHARACTER = "";

    /**
     * Do not allow this class to be instantiated.
     */
    private function __construct() {}
}