<?php

// Experiments to understand encoding errors

$input = 'Новый вид рода Sterculapion Rheinheimer, 1997 (Coleoptera: Apionidae) из Северо-Восточной Австралии';
$output = mb_convert_encoding($input, 'UTF-8', 'WINDOWS-1251');

echo $output . "\n";

?>
