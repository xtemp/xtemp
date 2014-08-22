<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputSecretElement.php created on 5. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class InputSecretElement extends InputField
{
	private $required;
	private $requiredMessage;
	
	protected function loadParams()
	{
		parent::loadParams();
		$this->required = $this->useAttrPlain("required", "false", array("true", "false"));
		$this->requiredMessage = $this->useAttrExpr("requiredMessage", 'Value required');
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function getFnCall()
	{
		$lbl = '$labels[' . $this->id . ']';
		$ret = 'addPassword(' . $this->id . ", isset($lbl)?$lbl:'')";
		//$ret .= '->setValue(' . $this->value->toPHP() . ')';
		if ($this->required == "true")
		{
			$ret .= '->setRequired(' . $this->requiredMessage->toPHP() . ')';
		}
		return $ret;
	}
}