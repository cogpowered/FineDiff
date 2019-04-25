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

interface OperationInterface
{
    /**
     * Copy code
     *
     * @var string
     */
    const COPY = 'c';

    /**
     * Delete code
     *
     * @var string
     */
    const DELETE = 'd';

    /**
     * Insert code
     *
     * @var string
     */
    const INSERT = 'i';

    /**
     * @return int
     */
    public function getFromLen();

    /**
     * @return int
     */
    public function getToLen();

    /**
     * @return string Operation code for this operation.
     */
    public function getOperationCode();
}
