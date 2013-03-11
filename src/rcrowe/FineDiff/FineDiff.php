<?php

namespace rcrowe\FineDiff;

use rcrowe\FineDiff\Granularity\GranularityInterface;
use rcrowe\FineDiff\Parser\ParserInterface;

/**
* FineDiff class
*/
class FineDiff
{
    /**
     * @var rcrowe\FineDiff\Parser\ParserInterface
     */
    protected $parser;

    public function __construct(GranularityInterface $granularity = null, ParserInterface $parser = null)
    {
        // Set the granularity of the diff
        $granularity OR $granularity = new Granularity\Character;

        // Set the opcode parser
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
    public function getDiff($from_text, $to_text)
    {
        return $this->parser->parse($from_text, $to_text);
    }
}
