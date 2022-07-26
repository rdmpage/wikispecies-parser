<?php

// Export CSL-JSON (in JSONL format, i.e. one JSON document per line) to RIS

require_once (dirname(__FILE__) . '/csl_utils.php');

$filename = 'Meyrick.json';

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
		
    $obj = json_decode($json);
    
    if ($obj && isset($obj->title))
    {
		if (preg_match('/exotic micro/i', $obj->title))
		{
			if ($obj->volume == 4)
			{
		
				$ris = csl_to_ris($obj);
	
				echo $ris;
			}
		}
	}	
    
	
}	


?>
