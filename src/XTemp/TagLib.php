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
	public static $xmlns = "";
	
	
	/**
	 * 
	 * @param \DOMElement $element
	 * @return XTemp\Tree\Component
	 */
	public function create($element)
	{
		$name = $element->nodeName;
		$fname = 'create' . ucfirst($name);
		$call = array($this, $fname);
		if (is_callable($call))
			return call_user_func($call, $element);
		else
			return $this->unknownElement($element);
	}
	
	public function unknownElement($element)
	{
		return null;
	}
	
	
	
}