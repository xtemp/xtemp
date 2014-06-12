<?php
/*
 * XTemp - XML Templating Engine for PHP
 * BodyElement.php created on 12. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class BodyElement extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return $this->renderStartElement() . $this->renderChildren() . $this->renderEndElement();
	}
	
}