<?php

namespace cogpowered\FineDiff\Render;

use cogpowered\FineDiff\Parser\OpcodesInterface;

interface RendererInterface
{
    public function process($from_text, OpcodesInterface $opcode);
    public function callback($opcode, $from, $from_offset, $from_len);
}