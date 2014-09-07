<?php
/*
* XTemp - XML Templating Engine for PHP
* ValidateLengthElement.php created on 7. 9. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

use Nette\Forms\Form;

/**
*
* @author      burgetr
*/
class ValidateLengthElement extends Validator
{
	private $minimum;
	private $maximum;

	protected function loadParams()
	{
		parent::loadParams();
		$this->minimum = $this->useAttrExpr('minimum', NULL);
		$this->maximum = $this->useAttrExpr('maximum', NULL);
	}
	
	public function beforeRender()
	{
		$p = $this->getParent();
		if ($p !== NULL)
		{
			$p->addControlParam("validate", get_called_class());
			$parms = array();
			if ($this->message) $parms['message'] = $this->message;
			if ($this->minimum) $parms['minimum'] = $this->minimum;
			if ($this->maximum) $parms['maximum'] = $this->maximum;
			$p->addControlParam("validate_p", $parms);
		}
	}

	public static function addToForm($form, $name, $params)
	{
		$msg = isset($params['message']) ? $params['message'] : NULL; 
		if (isset($params['minimum']))
			$form[$name]->addRule(Form::MIN_LENGTH, $msg, intval($params['minimum']));
		if (isset($params['maximum']))
			$form[$name]->addRule(Form::MAX_LENGTH, $msg, intval($params['maximum']));
	}
	
	
}