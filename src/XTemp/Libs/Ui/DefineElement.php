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
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->name = $this->requireAttr('name');
	}

	public function render()
	{
		return "def " . $this->name;
	}
	
}