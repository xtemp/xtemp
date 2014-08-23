<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputText.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class InputTextElement extends InputField
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
	
	public static function addToForm($form, $name, $label, $value)
	{
		$f = $form->addText($name, $label);
		if ($value)
			$f->setValue($value);
	}
	
	
	public function getFnCall()
	{
		$lbl = '$labels[' . $this->id . ']';
		$ret = 'addText(' . $this->id . ", isset($lbl)?$lbl:'')";
		$ret .= '->setValue(' . $this->value->toPHP() . ')';
		if ($this->required == "true")
		{
			$ret .= '->setRequired(' . $this->requiredMessage->toPHP() . ')';
		}
		return $ret;
	}
}