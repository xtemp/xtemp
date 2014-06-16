<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Entity.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 *
 * @author      burgetr
 */
class Entity extends Component
{
	protected $domNode;
	
	public function __construct($domNode)
	{
		parent::__construct();
		$this->domNode = $domNode;
	}
	
	public function render()
	{
		if ($this->domNode instanceof \DOMEntityReference)
		{
			//return $this->domNode->nodeName . ": " . $this->domNode->nodeValue;
			return '&' . $this->domNode->nodeName . ';';
		}
		else
			return '';
	}
	
}