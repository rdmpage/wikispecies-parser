<?php

// Export CSL-JSON (in JSONL format, i.e. one JSON document per line) to RIS

require_once (dirname(__FILE__) . '/csl_utils.php');

$filename = 'Meyrick.json';
$filename = 'Léon_Marc_Herminie_Fairmaire.jsonl';

$show_headings = true;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
		
    $obj = json_decode($json);
    
    if ($obj && isset($obj->title))
    {
		$row = csl_to_tsv($obj, $show_headings);
		echo $row . "\n";
		
		$show_headings = false;
	}	
}	

?>
