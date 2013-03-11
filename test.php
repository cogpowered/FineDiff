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
echo $opcodes.' ';
echo FineDiff::renderDiffToHTMLFromOpcodes($from_text, $opcodes);
echo '<hr />';


$opcode = (new rcrowe\FineDiff\Diff)->getOpcodes($from_text, $to_text);
echo $opcode.' ';

echo (new rcrowe\FineDiff\Render\Html)->process($from_text, $opcode);
echo '<hr />';


$opcode = (new rcrowe\FineDiff\Diff)->getOpcodes($from_text, $to_text);
echo $opcode.' ';

echo (new rcrowe\FineDiff\Render\Text)->process($from_text, $opcode);
echo '<hr />';


echo (new rcrowe\FineDiff\Diff)->render($from_text, $to_text);

