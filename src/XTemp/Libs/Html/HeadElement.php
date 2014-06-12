<?php
/*
 * XTemp - XML Templating Engine for PHP
 * HeadElement.php created on 12. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class HeadElement extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return $this->renderStartElement() . $this->renderChildren() . $this->renderEndElement();
	}
	
}