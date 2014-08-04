<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputField.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
abstract class InputField extends \XTemp\Tree\Element
{
	protected $id;
	protected $value;
	
	protected function loadParams()
	{
		$this->id = $this->checkId();
		$this->value = $this->requireAttrExpr('value');
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	abstract public function getFnCall();
	
}