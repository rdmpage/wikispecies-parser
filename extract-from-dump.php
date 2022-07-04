<?php

error_reporting(E_ALL);

// extract references from a Wikispecies dump

// To get individual example
// https://species.wikimedia.org/wiki/Special:Export/Silvio_Shigueo_Nihei

require_once (dirname(__FILE__) . '/reference_parser.php');

$filename = 'dump/specieswiki-20211220-pages-articles-multistream.xml';
$filename = 'examples/Minet.xml';
$filename = 'examples/Template-Gillung_&_Nihei,_2016.xml';
//$filename = 'examples/Cicadettini.xml';

$file_handle = fopen($filename, "r");

$debug = true;
//$debug = false;

$state = 0;
$page = '';
$title = '';
$refs = array();
$subject_type = '';

$timestamp = '';

$force = true;
//$force = false;

$count = 0;

while (!feof($file_handle)) 
{
	$line = fgets($file_handle);
	
	// echo "$state | $line\n";
	
	switch ($state)
	{
		case 0:
			if (preg_match('/^\s+<page>/', $line))
			{
				$state = 1;
				$page = '';
				$refs = array();
				$subject_type = 'unknown';
				$timestamp = '';
				$title = '';
				//echo ".\n";
			}
			break;
			
		case 1:
			if (preg_match('/^\s+<\/page>/', $line))
			{
				if ((count($refs) > 0))
				{
				
					$obj = new stdclass;
					
					$obj->id = str_replace(' ', '_', $title);
					$obj->id = str_replace('&amp;', '&', $obj->id);					
					$obj->title = $title;
					$obj->timestamp = $timestamp;
					$obj->type = $subject_type;
										
					foreach ($refs as $r)
					{
						$citation = new stdclass;
						$citation->string = $r;
						
						$citation->string = str_replace('</text>', '', $citation->string);
						
						$obj->references[] = $citation;
					}
					
					//print_r($obj);
					
					if (isset($obj->references))
					{
						process_references($obj);
						
						$reference_count = 1;
						
						foreach ($obj->references as $reference)
						{
							if (isset($reference->csl) && $reference->csl->status == 'ok')
							{
								// link to Wikispecies
								switch ($obj->type)
								{
									case 'reference':
										$reference->csl->id = 'https://species.wikimedia.org/wiki/' . $obj->id;									
										break;
								
									default:
										$reference->csl->id = 'https://species.wikimedia.org/wiki/' . $obj->id . '#' . $reference_count;
										break;
								}								
								
								// cleaning
								if (isset($reference->csl->{'container-title'}))
								{
									$reference->csl->{'container-title'} = preg_replace('/^\[\[/', '', $reference->csl->{'container-title'});
									$reference->csl->{'container-title'} = preg_replace('/\]\]$/', '', $reference->csl->{'container-title'});
								}
							
								//echo json_encode($reference->csl, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
								echo json_encode($reference->csl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
								
								$reference_count++;
							}
						}
						
						//print_r($obj);
					}

					$count++;
					
					if ($debug)
					{
						if ($count == 100) 
						{
							exit();
						}
					}
				}
							
				$state = 0;
			}
			else
			{
				$page .= $line;
				
				if (preg_match('/^\s*<title>(?<title>.*)<\/title>/', $line, $m))
				{
					//print_r($m);
					$title = $m['title']; 
				}

				// <timestamp>2015-10-29T19:34:16Z</timestamp>

				if (preg_match('/^\s*<timestamp>(?<timestamp>.*)<\/timestamp>/', $line, $m))
				{
					//print_r($m);
					$timestamp = $m['timestamp']; 										
				}
				
				if (preg_match('/^\*\s+\{\{a/', $line))
				{
					// possible reference
					
					$text = trim($line);
					$line = str_replace('</text>', '', $text);
					
					$refs[] = $text;
					//echo $title . "|" . $line . "\n";
				}
				
				// <text bytes="610" xml:space="preserve">
				if (preg_match('/<text bytes="\d+" xml:space="preserve">\s*\*\s+\{\{a/', $line))
				{
					// possible reference
					
					$text = trim($line);
					$text = str_replace('</text>', '', $text);
					
					$text = preg_replace('/<text bytes="\d+" xml:space="preserve">/', '', $text);
					
					$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
					
					$refs[] = $text;
					//echo $title . "|" . $line . "\n";
				}
				
				
				if (preg_match('/\[\[Category:Taxon authorities\]\]/', $line))
				{
					$subject_type = 'person';
				}
				
				if (preg_match('/\[\[Category:Reference templates\]\]/', $line))
				{
					$subject_type = 'reference';
				}
				
			}
			break;
				
		default:
			break;
			
	}
}

?>
