<?php

namespace rcrowe\FineDiff\Render;

use rcrowe\FineDiff\Parser\OpcodesInterface;

interface RendererInterface
{
    public function process($from_text, OpcodesInterface $opcode);
    public function callback($opcode, $from, $from_offset, $from_len);
}