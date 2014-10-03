<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Ajax.php created on 2. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Ajax;

/**
 *
 * @author      burgetr
 */
class Ajax extends \Xtemp\TagLib
{
	public static $xmlns = "http://github.com/xtemp/ns/ajax";
	
	public static function getResourcePaths()
	{
		return array("xtemp.ajax" => __DIR__ . "/resources/");
	}
	
}