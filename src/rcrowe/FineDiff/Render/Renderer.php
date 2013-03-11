<?php

namespace rcrowe\FineDiff\Render;

use rcrowe\FineDiff\Parser\OpcodesInterface;

abstract class Renderer implements RendererInterface
{
    public function process($from_text, OpcodesInterface $opcodes)
    {
        // Holds the generated string that is returned
        $output = '';

        $opcodes        = $opcodes->generate();
        $opcodes_len    = strlen($opcodes);
        $from_offset    = 0;
        $opcodes_offset = 0;

        while ($opcodes_offset < $opcodes_len) {

            $opcode = substr($opcodes, $opcodes_offset, 1);
            $opcodes_offset++;
            $n = intval(substr($opcodes, $opcodes_offset));

            if ($n) {
                $opcodes_offset += strlen(strval($n));
            } else {
                $n = 1;
            }

            if ($opcode === 'c') {
                // copy n characters from source
                $data = $this->callback('c', $from_text, $from_offset, $n);
                $from_offset += $n;
            } else if ($opcode === 'd') {
                // delete n characters from source
                $data = $this->callback('d', $from_text, $from_offset, $n);
                $from_offset += $n;
            } else /* if ( $opcode === 'i' ) */ {
                // insert n characters from opcodes
                $data = $this->callback('i', $opcodes, $opcodes_offset + 1, $n);
                $opcodes_offset += 1 + $n;
            }

            $output .= $data;
        }

        return $output;
    }
}