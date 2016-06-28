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

namespace bariew\FineDiff\Granularity;

use bariew\FineDiff\Delimiters;

/**
 * Character level granularity.
 */
class Character extends Granularity
{
    protected $delimiters = array(
        Delimiters::PARAGRAPH,
        Delimiters::SENTENCE,
        Delimiters::WORD,
        Delimiters::CHARACTER,
    );
}