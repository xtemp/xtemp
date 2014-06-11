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
			$rootComp = $this->buildTree($domRoot);
			$this->prepareRendering($rootComp);
			return $rootComp->render();
		}
		else
			return '';
	}
	
	public function buildTree($node)
	{
		if ($node instanceof \DOMElement)
		{
			$ret = $this->createComponent($node);
			if ($ret)
			{
				foreach ($node->childNodes as $child)
				{
					$ccomp = $this->buildTree($child);
					$ret->addChild($ccomp);
				}
				return $ret;
			}
			else
				throw new ComponentNotFoundException("Couldn't create component for tag " . $node->nodeName);
		}
		else if ($node instanceof \DOMText)
		{
			return new Tree\Content($node);
		}
	}
	
	public function prepareRendering($root)
	{
		$root->beforeRender();
		foreach ($root->getChildren() as $child)
			$this->prepareRendering($child);
	}
	
	private function createComponent($element)
	{
		$uri = $element->namespaceURI;
		if (isset($this->namespaces[$uri]))
		{
			$classname = $this->namespaces[$uri];
			$taglib = new $classname;
			return $taglib->create($element);
		}
		else
			throw new TagLibraryNotFoundException("Couldn't find tag library for namespace " . $uri);
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