<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ParamElement.php created on 17. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class ParamElement extends \XTemp\Tree\Element
{
	private $name;
	private $value;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->name = $this->requireAttrPlain('name');
		$this->value = $this->requireAttrExpr('value');
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function getValue()
	{
		return $this->value->toPHP();
	}
	
	public function render()
	{
		return '{var ' . $this->name . ' = ' . $this->getValue() . "}\n"; 
	}
	
}