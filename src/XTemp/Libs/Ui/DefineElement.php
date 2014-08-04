<?php
/*
 * XTemp - XML Templating Engine for PHP
 * DefineElement.php created on 13. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class DefineElement extends \XTemp\Tree\Element
{
	private $name;
	
	protected function loadParams()
	{
		$this->name = $this->requireAttrPlain('name');
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function render()
	{
		return '';
	}
	
}