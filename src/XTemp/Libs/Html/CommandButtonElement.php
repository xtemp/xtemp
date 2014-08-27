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
	
	protected function loadParams()
	{
		parent::loadParams();
		$this->action = $this->requireAttrExpr("action");
	}

	public function getAction()
	{
		return $this->action;
	}
	
	public function getMappingValue()
	{
		if ($this->action !== NULL)
			return $this->action->getLValueMapString();
		else
			return NULL;
	}
	
	public static function addToForm($form, $name, $label, $value, $params)
	{
		$form->addSubmit($name, $value);
	}
	
}