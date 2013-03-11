<?php

namespace cogpowered\FineDiff\Render;

use cogpowered\FineDiff\Parser\OpcodeInterface;

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