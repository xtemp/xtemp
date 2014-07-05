<?php
/*
 * XTemp - XML Templating Engine for PHP
 * AElement.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class AElement extends \XTemp\Tree\Element
{
	private $href;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->href = $this->requireAttrExpr('href');
	}
	
	public function render()
	{
		return $this->renderStartElement() . $this->renderChildren() . $this->renderEndElement();
	}

	protected function renderAttribute($name)
	{
		if ($name == "href")
			return 'href="{link ' . $this->href . '}"';
		else
			return parent::renderAttribute($name); 
	}
	
}