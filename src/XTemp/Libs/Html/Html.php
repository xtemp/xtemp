<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Html.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class Html extends \Xtemp\TagLib
{
	public static $xmlns = "http://www.w3.org/1999/xhtml";
	
	public function unknownElement($element)
	{
		return new Element($element);
	}
}