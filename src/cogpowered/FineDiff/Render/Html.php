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

use CogPowered\FineDiff\Parser\Operations\Operation;

class Html extends Renderer
{
    /**
     * @var array
     */
    protected $config_renderer = array();
#\$this->assertTrue\(is_a\(([^,]+),\s*(['"])([^"']+)\2\)\);
    /**
     * Html constructor.
     *
     * @param array $config_renderer
     */
    public function __construct(array $config_renderer = array())
    {
        $config_renderer += array(
            'encoding' => 'UTF-8',
            'del_prefix' => '<del>',
            'del_suffix' => '</del>',
            'ins_prefix' => '<ins>',
            'ins_suffix' => '</ins>',
            'quote_style' => ENT_COMPAT | (defined('ENT_HTML401') ? ENT_HTML401 : 0)
        );

        $this->config_renderer = $config_renderer;
    }

    public function callback($opcode, $from, $from_offset, $from_len)
    {
        switch ($opcode) {
            case Operation::COPY:
                return $this->onCopy($from, $from_offset, $from_len);
            case Operation::DELETE:
                return $this->onDelete($from, $from_offset, $from_len);
            case Operation::INSERT:
                return $this->onInsert($from, $from_offset, $from_len);
            default:
                throw new \InvalidArgumentException('Undefined operation code "'.$opcode.'"');
        }
    }

    /**
     * @param string $from
     * @param int    $from_offset
     * @param int    $from_len
     *
     * @return string
     */
    protected function onCopy($from, $from_offset, $from_len)
    {
        return $this->htmlentities(substr($from, $from_offset, $from_len));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function htmlentities($string)
    {
        return htmlentities(
            $string,
            $this->config_renderer['quote_style'],
            $this->config_renderer['encoding']
        );
    }

    /**
     * @param string $from
     * @param int    $from_offset
     * @param int    $from_len
     *
     * @return string
     */
    protected function onDelete($from, $from_offset, $from_len)
    {
        $deletion = substr($from, $from_offset, $from_len);

        return $this->wrap($this->htmlentities($deletion), 'del_prefix', 'del_suffix');
    }

    /**
     * @param string $string
     * @param string $prefix
     * @param string $suffix
     *
     * @return string
     */
    protected function wrap($string, $prefix, $suffix)
    {
        return $this->config_renderer[$prefix].$string.$this->config_renderer[$suffix];
    }

    /**
     * @param string $from
     * @param int    $from_offset
     * @param int    $from_len
     *
     * @return string
     */
    protected function onInsert($from, $from_offset, $from_len)
    {
        $insertion = substr($from, $from_offset, $from_len);

        return $this->wrap($this->htmlentities($insertion), 'ins_prefix', 'ins_suffix');
    }
}
