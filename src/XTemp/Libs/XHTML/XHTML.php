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
class XHTML extends \XTemp\TagLib
{
	public static $xmlns = "http://www.w3.org/1999/xhtml";

	//void elements according to the HTML5 specification
	public static $VOID_ELEMENTS = array('area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr');
	
	public function unknownElement(\DOMElement $element, \XTemp\Context $context)
	{
		if (in_array(strtolower($element->tagName), XHTML::$VOID_ELEMENTS))
			return new ElementVoid($element, $context);
		else
			return new Element($element, $context);
	}
	
}