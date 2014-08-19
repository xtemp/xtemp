<?php
/*
 * XTemp - XML Templating Engine for PHP
 * SelectBooleanCheckboxElement.php created on 19. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class SelectBooleanCheckboxElement extends InputField
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
	
	public function render()
	{
		return '{input ' . $this->id . ':}';
	}
	
	public function getFnCall()
	{
		$lbl = '$labels[' . $this->id . ']';
		$ret = 'addCheckbox(' . $this->id . ", isset($lbl)?$lbl:'')";
		$ret .= '->setValue(' . $this->value->toPHP() . ')';
		if ($this->required == "true")
		{
			$ret .= '->setRequired(' . $this->requiredMessage->toPHP() . ')';
		}
		return $ret;
	}
	
	public function setLabel(OutputLabelElement $label)
	{
		$label->setPartial(TRUE);
	}
	
}