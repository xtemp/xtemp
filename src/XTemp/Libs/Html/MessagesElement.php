<?php
/*
 * XTemp - XML Templating Engine for PHP
 * MessagesElement.php created on 27. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class MessagesElement extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return "\n" . '<div n:foreach="$flashes as $flash" class="flash {$flash->type}">{$flash->message}</div>' . "\n";
	}
	
}