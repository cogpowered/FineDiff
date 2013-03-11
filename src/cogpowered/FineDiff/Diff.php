<?php

/**
* FINE granularity DIFF
*
* Computes a set of instructions to convert the content of
* one string into another.
*
* Originally created by Raymond Hill (github.com/gorhill/PHP-FineDiff), brought up
* to date by Cog Powered (github.com/cogpowered/FineDiff).
*
* @copyright Copyright 2011 (c) Raymond Hill (http://raymondhill.net/blog/?p=441)
* @copyright Copyright 2013 (c) Robert Crowe (http://cogpowered.com)
* @link https://github.com/cogpowered/FineDiff
* @version 0.0.1
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

namespace cogpowered\FineDiff;

use cogpowered\FineDiff\Granularity\GranularityInterface;
use cogpowered\FineDiff\Render\RendererInterface;
use cogpowered\FineDiff\Parser\ParserInterface;
use cogpowered\FineDiff\Granularity\Character;
use cogpowered\FineDiff\Render\Html;
use cogpowered\FineDiff\Parser\Parser;

/**
* Diff class.
*/
class Diff
{
    /**
     * @var cogpowered\FineDiff\Granularity\GranularityInterface
     */
    protected $granularity;

    /**
     * @var cogpowered\FineDiff\Render\RendererInterface
     */
    protected $renderer;

    /**
     * @var cogpowered\FineDiff\Parser\ParserInterface
     */
    protected $parser;

    /**
     * Instantiate a new instance of Diff.
     *
     * @param cogpowered\FineDiff\Granularity\GranularityInterface $granularity Level of diff.
     * @param cogpowered\FineDiff\Render\RenderInterface           $renderer    Diff renderer.
     * @param cogpowered\FineDiff\Parser\ParserInterface           $parser      Parser used to generate opcodes.
     *
     * @throws cogpowered\FineDiff\Exceptions\GranularityCountException
     * @throws cogpowered\FineDiff\Exceptions\OperationException
     */
    public function __construct(GranularityInterface $granularity = null, RendererInterface $renderer = null, ParserInterface $parser = null)
    {
        // Set some sensible defaults

        // Set the granularity of the diff
        $this->granularity = ($granularity !== null) ? $granularity : new Character;

        // Set the renderer to use when calling Diff::render
        $this->renderer = ($renderer !== null) ? $renderer : new Html;

        // Set the diff parser
        $this->parser = ($parser !== null) ? $parser : new Parser($this->granularity);
    }

    /**
     * Returns the granularity object used by the parser.
     *
     * @return @cogpowered\FineDiff\Granularity\GranularityInterface
     */
    public function getGranularity()
    {
        return $this->parser->getGranularity();
    }

    /**
     * Set the granularity level of the parser.
     *
     * @param cogpowered\FineDiff\Granularity\GranularityInterface
     * @return void
     */
    public function setGranularity(GranularityInterface $granularity)
    {
        $this->parser->setGranularity($granularity);
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getParser()
    {
        return $this->parser;
    }

    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Gets the diff between two sets of text.
     *
     * Returns the opcode diff which can be used for example, to
     * to generate a HTML report of the differences.
     *
     * @return string
     */
    public function getOpcodes($from_text, $to_text)
    {
        return $this->parser->parse($from_text, $to_text);
    }

    public function render($from_text, $to_text)
    {
        // First we need the opcodes
        $opcodes = $this->getOpcodes($from_text, $to_text);

        return $this->renderer->process($from_text, $opcodes);
    }
}
