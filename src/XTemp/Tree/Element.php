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
	
	protected function renderStartElement()
	{
		$ret = '<' . $this->domElement->nodeName;
		foreach ($this->domElement->attributes as $attr)
			$ret .= ' ' . $attr->nodeName . '="' . $attr->nodeValue . '"';
		$ret .= '>';
		return $ret;
	}
	
	protected function renderEndElement()
	{
		return '</' . $this->domElement->nodeName . '>';
	}
	
	//================================= Attribute Utilities ===========================================
	
	protected function requireAttr($name)
	{
		if ($this->domElement->hasAttribute($name))
			return $this->domElement->getAttribute($name);
		else
			throw new \XTemp\MissingAttributeException("Missing attribute '$name' of the <{$this->domElement->nodeName}> element");
	}
	
}