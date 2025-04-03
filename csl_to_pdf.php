<?php



require_once ('csl_utils.php');

$filename = "all.jsonl";

$count = 0;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$json = trim(fgets($file_handle));
		
    $csl = json_decode($json);
    
    //print_r($csl);
    
    if (isset($csl->link))
    {
    	foreach ($csl->link as $link)
    	{
    		if (isset($link->{'content-type'}) && ($link->{'content-type'} == 'application/pdf'))
    		{
    			$pdf = $link->URL;
    			
    			// wayback machine
    			$pdf = str_replace("/http", "if_/http", $pdf);
    		
    			echo $pdf . "\n";
    		}
    	}
    }
    
    
    $count++;
    
    
    if ($count > 1000)    
    {
    	//exit();
    }
    
    
	
}	

?>
