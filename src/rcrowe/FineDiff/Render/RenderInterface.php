<?php

namespace rcrowe\FineDiff\Render;

use rcrowe\FineDiff\Parser\OpcodeInterface;

interface RenderInterface
{
    public function render(OpcodeInterface $opcode);
}