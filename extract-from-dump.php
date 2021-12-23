<?php

error_reporting(E_ALL);

// extract references from a Wikispecies dump

require_once (dirname(__FILE__) . '/reference_parser.php');

$filename = 'dump/specieswiki-20211220-pages-articles-multistream.xml';
//$filename = 'examples/Minet.xml';

$file_handle = fopen($filename, "r");


$debug = true;
$debug = false;

$state = 0;
$page = '';
$title = '';
$refs = array();
$is_authority = false;

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
				$is_authority = false;
				$timestamp = '';
				$title = '';
				//echo ".\n";
			}
			break;
			
		case 1:
			if (preg_match('/^\s+<\/page>/', $line))
			{
				
				// taxonomy
				$parent = '';
				if (preg_match('/== Taxonavigation ==\s+\{\{(?<parent>.*)\}\}/Uu', $page, $m))
				{
					$parent = $m['parent'];
				}
								
				if ((count($refs) > 0) || ($parent != ''))
				{
				
					$obj = new stdclass;
					
					$obj->id = str_replace(' ', '_', $title);
					$obj->title = $title;
					$obj->timestamp = $timestamp;
					
					if ($parent != '')
					{
						$obj->taxonavigation = $parent;
					}
					
					$obj->categories = array();
					
					/*
					// grab text
					$obj->text = $page;

					
					// trim
					$pos = strpos($obj->text, '<text xml:space="preserve">');
					if ($pos === false)
					{
						//echo "not found;\n"; exit();
					}
					else
					{
						$pos += strlen('<text xml:space="preserve">');
						$obj->text = substr($obj->text, $pos);
					}
					
					$pos = strpos($obj->text, '</text>');
					if ($pos === false)
					{
					}
					else
					{
						$obj->text = substr($obj->text, 0, $pos);
					}
					*/

					foreach ($refs as $r)
					{
						$citation = new stdclass;
						$citation->string = $r;
						
						$citation->string = str_replace('</text>', '', $citation->string);
						
						$obj->references[] = $citation;
					}
					
					if ($is_authority)
					{
						//echo "*** person ***\n";
					}
					
					if (isset($obj->references))
					{
						process_references($obj);
						
						foreach ($obj->references as $reference)
						{
							if (isset($reference->csl) && $reference->csl->status == 'ok')
							{
								// link to Wikispecies
								$reference->csl->source = $obj->id;
							
								//echo json_encode($reference->csl, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
								echo json_encode($reference->csl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
							}
						}
						
						//print_r($obj);
					}

					$count++;
					
					if ($debug)
					{
						if ($count == 10) 
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
					$is_authority = true;
				}

				if (preg_match('/\[\[Category:\s*(?<category>.*)\]\]/Uu', $line, $m))
				{
					$obj->categories[] = $m['category'];
				}			
				
			}
			break;
				
		default:
			break;
			
	}
}

?>
