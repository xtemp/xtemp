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
	 * Creates a component from a DOM element. The following methods of the component creation
	 * are tried:
	 * <ol>
	 * <li>Using the <code>create<i>Name</i>($element)</code> method if defined in this class.</li>
	 * <li>Creating an instance of a <code><i>Name</i>Element</code> class in the same PHP namespace as this class
	 * if such a class exists and it's derived from \XTemp\Tree\Element.</li>
	 * <li>Using the <code>unknownElement($element)</code> method when everything fails.</li>
	 * </ol>
	 * 
	 * @param \DOMElement $element
	 * @return XTemp\Tree\Component the created component or NULL when the creation fails
	 */
	public function create($element)
	{
		$name = $element->nodeName;
		if (($pp = strpos($name, ':')) !== FALSE) //strip namespace from the tag name
			$name = substr($name, $pp + 1);
		
		//try the factory function
		$fname = 'create' . ucfirst($name);
		$call = array($this, $fname);
		if (is_callable($call))
		{
			return call_user_func($call, $element);
		}
		else //try the class by name
		{
			//obtain current namespace
			$ns = get_class($this) . ":";
			if (($ci = strrpos($ns, '\\')) !== FALSE)
				$ns = substr($ns, 0, $ci);
			//check the class
			$cname = $ns . '\\' . ucfirst($name) . 'Element';
			if (class_exists($cname, TRUE))
			{
				return new $cname($element);
			}
			else
			{
				//couldn't create the instance, use the fallback function
				return $this->unknownElement($element);
			}
		}
	}
	
	public function unknownElement($element)
	{
		return null;
	}
	

	
}