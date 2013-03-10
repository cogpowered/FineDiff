<?php

namespace rcrowe\FineDiff;

class FineDiffInsertOp extends FineDiffOp
{
    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getFromLen()
    {
        return 0;
    }

    public function getToLen()
    {
        return strlen($this->text);
    }

    public function getText()
    {
        return $this->text;
    }

    public function getOpcode()
    {
        $to_len = strlen($this->text);

        if ( $to_len === 1 ) {
            return "i:{$this->text}";
        }

        return "i{$to_len}:{$this->text}";
    }
}