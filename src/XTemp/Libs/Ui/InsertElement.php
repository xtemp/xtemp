<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InsertElement.php created on 13. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class InsertElement extends \XTemp\Tree\Element
{
	private $name;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->name = $this->requireAttr('name');
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function render()
	{
		return $this->renderChildren();
	}
	
}