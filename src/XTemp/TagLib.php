<?php
/*
 * XTemp - XML Templating Engine for PHP
 * TagLib.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp;

/**
 *
 * @author      burgetr
 */
abstract class TagLib
{
	public $xmlns = "";
	
	
	/**
	 * 
	 * @param unknown $element
	 * @return XTemp\Tree\Component
	 */
	public function process($element)
	{
		$name = $element->nodeName;
		$fname = 'process' . ucfirst($name);
		$call = array($this, $fname);
		if (is_callable($call))
			call_user_func($call, $element);
		else
			$this->unknown($element);
	}
	
	public function unknown($element)
	{
		
	}
	
	
	
}