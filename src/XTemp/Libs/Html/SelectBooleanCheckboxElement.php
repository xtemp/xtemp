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
	
	public function setLabel(OutputLabelElement $label)
	{
		$label->setPartial(TRUE);
	}
	
	public static function addToForm($form, $name, $label, $value, $params)
	{
		$f = $form->addCheckbox($name, $label);
		if ($value)
			$f->setValue($value);
	}
	
}