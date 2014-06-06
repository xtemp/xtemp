<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Filter.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp;

require __DIR__ . '/Libs/libs.php';

/**
 *
 * @author      burgetr
 */
class Filter
{
	protected $namespaces;
	
	public function __construct()
	{
		$this->buildNamespaceTable();
	}
	
	public function process($src)
	{
		$document = $this->loadXML($src);
		$domRoot = $document->documentElement;
		if ($domRoot)
		{
			print_r($domRoot);
		}
		else
			return '';
	}
	
	public function buildTree($element)
	{
		$uri = $element->namespaceURI;
		if (isset($this->namespaces[$uri]))
		{
			$classname = $this->namespaces[$uri];
			$taglib = new $classname;
			return $taglib->process($element);  
		}
		else
			return NULL;
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
	
	public function buildNamespaceTable()
	{
		$this->namespaces = array();
		foreach (get_declared_classes() as $class)
		{
	        if (is_subclass_of($class, 'XTemp\TagLib'))
	        {
	        	$ref = new \ReflectionClass($class);
	        	$xmlns = $ref->getStaticPropertyValue('xmlns');
	            $this->namespaces[$xmlns] = $class;
	        }
	    }
	}
	
}