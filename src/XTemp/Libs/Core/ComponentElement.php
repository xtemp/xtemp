<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ComponentElement.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class ComponentElement extends \XTemp\Tree\Element
{
	private $name;
	
	protected function loadParams()
	{
		$this->name = $this->requireAttrExpr('name');
	}
	
	public function render()
	{
		return '{control ' . $this->name->toPHP() . '}';
	}

}