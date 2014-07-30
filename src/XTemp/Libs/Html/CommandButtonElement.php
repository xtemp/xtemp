<?php
/*
 * XTemp - XML Templating Engine for PHP
 * CommandButtonElement.php created on 30. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class CommandButtonElement extends InputField
{
	private $action;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->action = $this->requireAttrPlain("action");
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		return '{input ' . $this->id . '}';
	}
	
	public function getAction()
	{
		return $this->action;
	}
	
	public function getFnCall()
	{
		$ret = 'addSubmit(' . $this->id . ", " . $this->value->toPHP() .")";
		return $ret;
	}
}