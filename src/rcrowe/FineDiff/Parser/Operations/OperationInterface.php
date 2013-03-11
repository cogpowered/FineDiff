<?php

namespace rcrowe\FineDiff\Parser\Operations;

interface OperationInterface
{
    public function getFromLen();
    public function getToLen();
    public function getOpcode();
}