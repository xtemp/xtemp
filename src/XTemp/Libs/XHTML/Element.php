<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Element.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\XHTML;

/**
 * A standard XHTML element that is just rendered including its contents.
 * 
 * @author      burgetr
 */
class Element extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return $this->renderStartElement() . $this->renderChildren() . $this->renderEndElement();
	}
	
}