<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Filter.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp;

/**
 *
 * @author      burgetr
 */
class Filter
{
	
	public function process($src)
	{
		$document = $this->loadXML($src);
		$domRoot = $document->documentElement;
		if ($domRoot)
		{
		}
		else
			return '';
	}
	
	private function loadXML($inputXML) 
	{
		$dom = new \DOMDocument();
	
		libxml_use_internal_errors(true);
		if (!$dom->loadxml($inputXML)) {
			$errors = libxml_get_errors();
			throw new \XTemp\XMLParseException($errors);
		}
		libxml_clear_errors();
	
		return $dom;
	}
	
}