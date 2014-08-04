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
	
	/**
	 * Returns the LValue mapped to this input field
	 * @return string the LValue map string or NULL when no mapping is available
	 */
	public function getMappingValue()
	{
		if ($this->value !== NULL && $this->value->isLValue())
			return implode(':', $this->value->getLValueIdentifiers());
		else
			return NULL;
	}
	
	abstract public function getFnCall();
	
}