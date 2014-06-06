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
class Element extends Component
{
	protected $domElement;
	
	public function __construct($domElement)
	{
		parent::__construct();
		$this->domElement = $domElement;
	}
	
	//================================= Utilities ===========================================
	
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
	
}