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
	
	public function render()
	{
		if ($this->form->inPhpMode())
			return $this->renderPhpControl();
		else
			return '{input ' . $this->id . ':}';
	}
	
	protected function renderPhpControl()
	{
		$this->addControlParam('partial', 1);
		return parent::renderPhpControl();
	}
	
	public static function addToForm($form, $name, $label, $value, $params)
	{
		$f = $form->addCheckbox($name, $label);
		if ($value)
			$f->setValue($value);
		if (isset($params['classes']))
			$f->getControlPrototype()->addClass($params['classes']);
	}
	
}