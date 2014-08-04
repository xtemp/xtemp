<?php
/*
 * XTemp - XML Templating Engine for PHP
 * XHTML.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\XHTML;

/**
 * Standard XHTML tag library.
 * 
 * @author      burgetr
 */
class XHTML extends \Xtemp\TagLib
{
	public static $xmlns = "http://www.w3.org/1999/xhtml";
	
	public function unknownElement(\DOMElement $element, \XTemp\Context $context)
	{
		return new Element($element, $context);
	}
	
}