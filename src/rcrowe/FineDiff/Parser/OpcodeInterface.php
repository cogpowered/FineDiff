<?php

namespace rcrowe\FineDiff\Parser;

interface OpcodeInterface
{
    public function __construct(array $opcodes);
    public function getOpcodes();
    public function generate();
    public function __toString();
}