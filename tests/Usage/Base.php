<?php

namespace FineDiffTests\Usage;

use FineDiffTests\TestCase;

abstract class Base extends TestCase
{
    protected function getFile($file)
    {
        $txt = file_get_contents(__DIR__.'/Resources/'.$file.'.txt');
        $txt = explode('==========', $txt);

        $from            = trim($txt[0]);
        $to              = trim($txt[1]);
        $operation_codes = trim($txt[2]);
        $html            = trim($txt[3]);

        return array($from, $to, $operation_codes, $html);
    }
}
