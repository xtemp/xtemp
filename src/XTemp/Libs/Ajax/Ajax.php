<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Ajax.php created on 2. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Ajax;

use XTemp\Tree\Element;
use XTemp\Tree\LocalResource;
use XTemp\Tree\PublicResource;

/**
 *
 * @author      burgetr
 */
class Ajax extends \Xtemp\TagLib
{
	public static $xmlns = "http://xtemp.github.io/ns/ajax";
	
	public static function getResourcePaths()
	{
		return array("xtemp.ajax" => __DIR__ . "/resources/");
	}

	/**
	 * Adds standard resources (now jQuery and the ajax helper script) to the
	 * given element. This allows having all the parametres (jQuery version etc.)
	 * in a single place.
	 * @param Element $element
	 */
	public static function requireStdResources(Element $element)
	{
		$element->addResource(new PublicResource("jquery", "1.6.4", "http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js", "text/javascript"));
		$element->addResource(new LocalResource("xtemp.ajax", "ajax.js", "text/javascript"));
	}
	
}