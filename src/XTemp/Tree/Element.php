<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Element.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 *
 * @author      burgetr
 */
abstract class Element extends Component
{
	protected $domElement;
	
	public function __construct($domElement)
	{
		parent::__construct();
		$this->domElement = $domElement;
	}
	
	//================================= Rendering Utilities ===========================================
	
	public function getSimpleName()
	{
		$name = $this->domElement->nodeName;
		if ($pp = strpos($name, ':') !== FALSE) //strip namespace from the tag name
			$name = substr($name, $pp + 1);
		return $name;
	}
	
	protected function renderStartElement()
	{
		$ret = '<' . $this->getSimpleName();
		foreach ($this->domElement->attributes as $attr)
			$ret .= ' ' . $this->renderAttribute($attr->nodeName);
		$ret .= '>';
		return $ret;
	}
	
	protected function renderEndElement()
	{
		return '</' . $this->getSimpleName() . '>';
	}

	protected function renderAttribute($name)
	{
		return $name . '="' . $this->domElement->getAttribute($name) . '"';
	}
	
	//================================= Attribute Utilities ===========================================
	
	protected function requireAttr($name)
	{
		if ($this->domElement->hasAttribute($name))
			return $this->domElement->getAttribute($name);
		else
			throw new \XTemp\MissingAttributeException("Missing attribute '$name' of the <{$this->domElement->nodeName}> element");
	}
	
	protected function useAttr($name, $default)
	{
		if ($this->domElement->hasAttribute($name))
			return $this->domElement->getAttribute($name);
		else
			return $default;
	}
}