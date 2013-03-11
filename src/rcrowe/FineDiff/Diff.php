<?php

namespace rcrowe\FineDiff;

use rcrowe\FineDiff\Granularity\GranularityInterface;
use rcrowe\FineDiff\Parser\ParserInterface;
use rcrowe\FineDiff\Render\RendererInterface;

/**
* FineDiff class
*/
class Diff
{
    /**
     * @var rcrowe\FineDiff\Granularity\GranularityInterface
     */
    protected $granularity;

    /**
     * @var rcrowe\FineDiff\Render\RendererInterface
     */
    protected $renderer;

    /**
     * @var rcrowe\FineDiff\Parser\ParserInterface
     */
    protected $parser;

    public function __construct(GranularityInterface $granularity = null, RendererInterface $renderer = null, ParserInterface $parser = null)
    {
        // Set the granularity of the diff
        $granularity OR $granularity = new Granularity\Character;
        $this->granularity = $granularity;

        // Set the renderer to use when calling Diff::render
        $renderer OR $renderer = new Render\Html;
        $this->renderer = $renderer;

        // Set the diff parser
        $parser OR $parser = new Parser\Parser($granularity);
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
