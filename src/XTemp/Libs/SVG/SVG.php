<?php
/*
 * XTemp - XML Templating Engine for PHP
 * SVG.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\SVG;

/**
 * Standard SVG tag library.
 * 
 * @author      burgetr
 */
class SVG extends \Xtemp\TagLib
{
	public static $xmlns = "http://www.w3.org/2000/svg";
	
	public function unknownElement(\DOMElement $element, \XTemp\Context $context)
	{
		return new Element($element, $context);
	}
	
}