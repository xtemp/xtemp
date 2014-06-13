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
	
	public function process($src, $file = NULL)
	{
		$tree = $this->buildTree($src, $file);
		if ($tree)
		{
			if (!$tree->getRoot())
			{
				return "Empty tree for $file";
			}
			$this->prepareRendering($tree->getRoot());
			return $tree->render();
		}
		else
			return '';
	}
	
	public function buildTree($src, $file = NULL)
	{
		$document = $this->loadXML($src);
		$domRoot = $document->documentElement;
		if ($domRoot)
		{
			$tree = new Tree\ComponentTree($file);
			$tree->setRoot($this->buildSubtree($tree, $domRoot));
			return $tree;
		}
		else
			return NULL;
	} 
	
	public function buildSubtree($tree, $node)
	{
		if ($node instanceof \DOMElement)
		{
			$ret = $this->createComponent($node);
			if ($ret)
			{
				$ret->setTree($tree);
				foreach ($node->childNodes as $child)
				{
					$ccomp = $this->buildSubtree($tree, $child);
					if ($ccomp)
						$ret->addChild($ccomp);
				}
				return $ret;
			}
			else
				throw new ComponentNotFoundException("Couldn't create component for tag " . $node->nodeName);
		}
		else if ($node instanceof \DOMText)
		{
			$ret = new Tree\Content($node);
			$ret->setTree($tree);
			return $ret;
		}
		else
			return NULL; //remaining node types (comments etc)
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
		if (!$uri)
		{
			throw new TagLibraryNotFoundException("Namespace for <" . $element->nodeName . "> is not defined");
		}
		else if (isset($this->namespaces[$uri]))
		{
			$classname = $this->namespaces[$uri];
			$taglib = new $classname;
			return $taglib->create($element);
		}
		else
		{
			throw new TagLibraryNotFoundException("Couldn't find tag library for namespace " . $uri);
		}
	}
	
	private function loadXML($inputXML) 
	{
		$dom = new \DOMDocument();
		$dom->substituteEntities = false;
		libxml_use_internal_errors(true);
		if (!$dom->loadXML($inputXML)) {
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