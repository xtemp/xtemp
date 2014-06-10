<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Content.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 *
 * @author      burgetr
 */
class Content extends Component
{
	protected $domNode;
	
	public function __construct($domNode)
	{
		parent::__construct();
		$this->domNode = $domNode;
	}
	
	public function render()
	{
		if ($this->domNode instanceof \DOMText)
		{
			return $this->domNode->nodeValue;
		}
		else
			return '';
	}
	
}