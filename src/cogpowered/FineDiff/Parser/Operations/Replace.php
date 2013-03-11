<?php

namespace cogpowered\FineDiff\Parser\Operations;

class Replace implements OperationInterface
{
    public function __construct($fromLen, $text)
    {
        $this->fromLen = $fromLen;
        $this->text    = $text;
    }

    public function getFromLen()
    {
        return $this->fromLen;
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
        if ($this->fromLen === 1) {
            $del_opcode = 'd';
        } else {
            $del_opcode = "d{$this->fromLen}";
        }

        $to_len = strlen($this->text);

        if ($to_len === 1) {
            return "{$del_opcode}i:{$this->text}";
        }

        return "{$del_opcode}i{$to_len}:{$this->text}";
    }
}