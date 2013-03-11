<?php

namespace cogpowered\FineDiff;

use cogpowered\FineDiff\Granularity\GranularityInterface;
use cogpowered\FineDiff\Render\RendererInterface;
use cogpowered\FineDiff\Parser\ParserInterface;
use cogpowered\FineDiff\Granularity\Character;
use cogpowered\FineDiff\Render\Html;
use cogpowered\FineDiff\Parser\Parser;

/**
* FineDiff class
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

    public function __construct(GranularityInterface $granularity = null, RendererInterface $renderer = null, ParserInterface $parser = null)
    {
        // Set some sensible defaults

        // Set the granularity of the diff
        $granularity OR $granularity = new Character;
        $this->granularity = $granularity;

        // Set the renderer to use when calling Diff::render
        $renderer OR $renderer = new Html;
        $this->renderer = $renderer;

        // Set the diff parser
        $parser OR $parser = new Parser($granularity);
        $this->parser = $parser;
    }

    public function getGranularity()
    {
        return $this->parser->getGranularity();
    }

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
