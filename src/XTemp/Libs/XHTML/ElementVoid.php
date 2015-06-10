<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ElementVoid.php created on 10. 6. 2015 by burgetr
 */

namespace XTemp\Libs\XHTML;

/**
 * A void XHTML element, just a single tag is rendered.
 * 
 * @author      burgetr
 */
class ElementVoid extends \XTemp\Tree\Element
{
	
	public function render()
	{
		return $this->renderStartElement();
	}
	
}