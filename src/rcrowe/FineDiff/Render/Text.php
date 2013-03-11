<?php

namespace rcrowe\FineDiff\Render;

use rcrowe\FineDiff\Parser\OpcodeInterface;

class Text extends Renderer
{
    public function callback($opcode, $from, $from_offset, $from_len)
    {
        if ($opcode === 'c' || $opcode === 'i') {
            return substr($from, $from_offset, $from_len);
        }

        return '';
    }
}