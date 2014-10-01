<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ElseElement.php created on 1. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class ElseElement extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return "\n{else}\n" . $this->renderChildren() . "\n";
	}
	
}