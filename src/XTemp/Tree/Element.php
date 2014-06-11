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
	
	protected $attributes;
	
	public function __construct($domElement)
	{
		parent::__construct();
		$this->domElement = $domElement;
		$this->loadAttributes();
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
		$attrs = trim($this->renderAttributes());
		if ($attrs)
			$attrs = ' ' . $attrs;
		return '<' . $this->getSimpleName() . $attrs . '>';
	}
	
	protected function renderEndElement()
	{
		return '</' . $this->getSimpleName() . '>';
	}

	protected function renderAttributes()
	{
		$ret = '';
		foreach ($this->attributes as $name => $value)
			$ret .= ' ' . $this->renderAttribute($name);
		return $ret;
	}
	
	protected function renderAttribute($name)
	{
		return $name . '="' . $this->attributes[$name] . '"';
	}
	
	//================================= Attribute Utilities ===========================================

	protected function loadAttributes()
	{
		$this->attributes = array();
		foreach ($this->domElement->attributes as $attr)
			$this->attributes[$attr->nodeName] = $attr->nodeValue;
	}
	
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