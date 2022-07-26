<?php

// Fetch pages direct from Wiki to create a "mini dump" of references we are 
// interested in, such as publications by a particular author. 

error_reporting(E_ALL);

function get($url, $format = '')
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	if ($format != '')
	{
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: " . $format));	
	}
	
	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
	
	curl_close($ch);
	
	return $response;
}

$page_names = array('Edward_Meyrick');

$include_transclusions = true;
//$include_transclusions = false;


while (count($page_names) > 0)
{
	$page_name = array_pop($page_names);
	
	$url = 'https://species.wikimedia.org/w/index.php?title=Special:Export&pages=' . $page_name;

	$xml = get($url);
	
	echo $xml;

	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);

	$xpath->registerNamespace("wiki", "http://www.mediawiki.org/xml/export-0.10/");
			
	$nodeCollection = $xpath->query ("//wiki:text");
	foreach($nodeCollection as $node)
	{
		// get text
		$text = $node->firstChild->nodeValue;		
		$lines = explode("\n", $text);
		
		foreach ($lines as $line)
		{
			if ($include_transclusions)
			{
				// transcluded references
				$matched = false;
				if (!$matched)
				{
					if (preg_match('/^(\*\s+)?\{\{(?<refname>[A-Z][\p{L}]+([,\s&;[a-zA-Z]+)[0-9]{4}[a-z]?)\}\}$/u', trim($line), $m))
					{
						$refname = $m['refname'];
						$refname = str_replace(' ', '_', $refname);
						$refname = str_replace('&', '%26', $refname);	
					
						$page_names[] = 'Template:' . $refname;	
						
						$matched = true;	
					}			
				}

				if (!$matched)
				{
					if (preg_match('/^\{\{(?<refname>[A-Z][\p{L}]+(.*)\s+[0-9]{4}[a-z]?)\}\}$/u', trim($line), $m))
					{
						$refname = $m['refname'];
						$refname = str_replace(' ', '_', $refname);
						$refname = str_replace('&', '%26', $refname);	
					
						$page_names[] = 'Template:' . $refname;	
						
						$matched = true;	
					}			
				}
			}						
		}	
	}
									
	$include_transclusions = false; // only do this the first time

}

?>

