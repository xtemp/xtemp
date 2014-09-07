<?php
/*
 * XTemp - XML Templating Engine for PHP
 * FlashMessagesElement.php created on 27. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class FlashMessagesElement extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return "\n" . '<div n:foreach="$flashes as $flash" class="flash {$flash->type}">{$flash->message}</div>' . "\n";
	}
	
}