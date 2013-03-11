<?php

namespace cogpowered\FineDiff\Parser;

interface OpcodesInterface
{
    public function __construct(array $opcodes);
    public function getOpcodes();
    public function generate();
    public function __toString();
}