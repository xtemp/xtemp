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
		if ($pp = strpos($name, ':') !== FALSE) //strip namespace from the tag name
			$name = substr($name, $pp + 1);
		$fname = 'create' . ucfirst($name);
		$call = array($this, $fname);
		if (is_callable($call))
			return call_user_func($call, $element);
		else
			return $this->unknownElement($element);
			//throw new \XTemp\ComponentNotFoundException("calling " . $fname);
	}
	
	public function unknownElement($element)
	{
		return null;
	}
	

	
}