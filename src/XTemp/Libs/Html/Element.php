<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Element.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class Element extends \XTemp\Tree\Element
{
	
	public function render()
	{
		$this->renderStartElement();
		$this->renderChildren();
		$this->renderEndElement();
	}
	
}