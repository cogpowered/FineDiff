<?php

namespace cogpowered\FineDiff\Parser\Operations;

interface OperationInterface
{
    public function getFromLen();
    public function getToLen();
    public function getOpcode();
}