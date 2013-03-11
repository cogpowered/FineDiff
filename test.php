<?php

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


$opcode = (new cogpowered\FineDiff\Diff)->getOpcodes($from_text, $to_text);
echo $opcode.' ';

echo (new cogpowered\FineDiff\Render\Html)->process($from_text, $opcode);
echo '<hr />';


$opcode = (new cogpowered\FineDiff\Diff)->getOpcodes($from_text, $to_text);
echo $opcode.' ';

echo (new cogpowered\FineDiff\Render\Text)->process($from_text, $opcode);
echo '<hr />';


echo (new cogpowered\FineDiff\Diff)->render($from_text, $to_text);

