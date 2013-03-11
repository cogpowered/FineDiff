<?php

namespace rcrowe\FineDiff\Render;

use rcrowe\FineDiff\Parser\OpcodeInterface;

class Html extends Renderer
{
    public function callback($opcode, $from, $from_offset, $from_len)
    {
        if ($opcode === 'c') {
            $html = htmlentities(htmlentities(substr($from, $from_offset, $from_len)));
        } else if ($opcode === 'd') {

            $deletion = substr($from, $from_offset, $from_len);

            if (strcspn($deletion, " \n\r") === 0) {
                $deletion = str_replace(array("\n","\r"), array('\n','\r'), $deletion);
            }

            $html = '<del>'.htmlentities(htmlentities($deletion)).'</del>';

        } else /* if ( $opcode === 'i' ) */ {
            $html = '<ins>'.htmlentities(htmlentities(substr($from, $from_offset, $from_len))).'</ins>';
        }

        return $html;
    }
}