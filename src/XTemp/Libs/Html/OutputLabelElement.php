<?php
/*
 * XTemp - XML Templating Engine for PHP
 * OutputLabelElement.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class OutputLabelElement extends \XTemp\Tree\Element
{
	private $for;
	private $value;
	
	private $columns;
	private $other;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->for = $this->requireAttrExpr('for');
		$this->value = $this->requireAttrExpr('value');
	}

	public function getFor()
	{
		return $this->for;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		return '{label ' . $this->for . '}{= ' . $this->value . '}{/label}';
	}
	
}