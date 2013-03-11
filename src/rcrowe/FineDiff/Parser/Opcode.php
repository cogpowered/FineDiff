<?php

namespace rcrowe\FineDiff\Parser;

class Opcode implements OpcodeInterface
{
    protected $opcodes = array();

    public function __construct(array $opcodes)
    {
        // Ensure that all elements of the array
        // are of the correct type
        foreach ($opcodes as $opcode) {
            if (!is_a($opcode, 'rcrowe\FineDiff\Parser\Operations\OperationInterface')) {
                throw new \Exception('ERROR PUNK!');
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