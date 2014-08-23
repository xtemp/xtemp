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
	
	public static function addToForm($form, $name, $label, $value)
	{
		$f = $form->addCheckbox($name, $label);
		$f->setValue($value);
		/*if ($this->required == "true")
		{
			$f->setRequired(' . $this->requiredMessage->toPHP() . ')';
		}*/
	}
	
	public function setLabel(OutputLabelElement $label)
	{
		$label->setPartial(TRUE);
	}
	
}