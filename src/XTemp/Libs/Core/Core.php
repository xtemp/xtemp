<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Core.php created on 10. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class Html extends \Xtemp\TagLib
{
	public static $xmlns = "http://github.com/radkovo/xtemp/ns/core";
	
	public function createA($element)
	{
		return new AElement($element);
	}
	
	public function createIf($element)
	{
		return new IfElement($element);
	}
	
	public function createOut($element)
	{
		return new OutElement($element);
	}
	
	
}