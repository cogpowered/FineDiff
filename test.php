<?php

include 'src/finediff.php';
include 'vendor/autoload.php';


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
var_dump($opcodes);
echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);
echo '<hr />';


$opcode = (new rcrowe\FineDiff\Diff)->getOpcode($from_text, $to_text, new rcrowe\FineDiff\Granularity\Word);
echo $opcode;

echo (new rcrowe\FineDiff\Render\Html)->render($opcode);

// echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);


// $granularity = new rcrowe\FineDiff\Granularity\Word;
// var_dump($granularity->getDelimiters());
