<?php

// Experiments to understand encoding errors

$input = '����� ��� ���� Sterculapion Rheinheimer, 1997 (Coleoptera: Apionidae) �� ������-��������� ���������';
$output = mb_convert_encoding($input, 'UTF-8', 'WINDOWS-1251');

echo $output . "\n";

?>
