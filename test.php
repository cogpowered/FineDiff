<?php

include 'vendor/autoload.php';

use rcrowe\FineDiff\FineDiff as FineDiff;

$from_text = 'Hello worlds';
$to_text   = 'Hello2 world';

$css = <<<CSS
<style>
del {
    text-decoration: none;
    background: #fdd;
    color: red;
}
ins {
    text-decoration: none;
    background: #dfd;
    color: green;
}
</style>
CSS;
echo $css;

$opcodes = FineDiff::getDiffOpcodes($from_text, $to_text);
echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);
