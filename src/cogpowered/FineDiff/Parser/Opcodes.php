<?php

namespace cogpowered\FineDiff\Parser;

use cogpowered\FineDiff\Exceptions\OperationException;

class Opcodes implements OpcodesInterface
{
    protected $opcodes = array();

    public function __construct(array $opcodes)
    {
        // Ensure that all elements of the array
        // are of the correct type
        foreach ($opcodes as $opcode) {
            if (!is_a($opcode, 'cogpowered\FineDiff\Parser\Operations\OperationInterface')) {
                throw new OperationException('Invalid opcode object');
            }

            $this->opcodes[] = $opcode->getOpcode();
        }
    }

    public function getOpcodes()
    {
        return $this->opcodes;
    }

    public function generate()
    {
        return implode('', $this->opcodes);
    }

    public function __toString()
    {
        return $this->generate();
    }
}