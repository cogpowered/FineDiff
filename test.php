<?php

include 'src/finediff.php';
include 'vendor/autoload.php';

// use rcrowe\FineDiff\FineDiff as FineDiff;
use rcrowe\FineDiff\FineDiff as FineDiffTwo;

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

$opcodes = FineDiff::getDiffOpcodes($from_text, $to_text, new rcrowe\FineDiff\Granularity\Character);
var_dump($opcodes);
echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);
echo '<hr />';


$opcodes = (new FineDiffTwo)->getDiff($from_text, $to_text);
echo $opcodes;

echo (new rcrowe\FineDiff\Render\Html)->render($opcodes);

// echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);


// $granularity = new rcrowe\FineDiff\Granularity\Word;
// var_dump($granularity->getDelimiters());
