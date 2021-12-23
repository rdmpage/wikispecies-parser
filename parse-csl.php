<?php



require_once ('csl_utils.php');

$filename = "all.jsonl";

$count = 0;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
		
    $obj = json_decode($json);
    
    //print_r($obj);
    
    // $ris = csl_to_ris($obj);
    
    //echo $ris;
    
	$tsv = csl_to_tsv($obj, $count == 0);
    
    echo $tsv . "\n";   
    
    $count++;
    
    /*
    if ($count > 100)    
    {
    	exit();
    }
    */
    
	
}	

?>
