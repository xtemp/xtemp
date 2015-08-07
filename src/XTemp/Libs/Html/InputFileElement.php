<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputFileElement.php created on 5. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class InputFileElement extends InputField
{
	
	protected function loadParams()
	{
		parent::loadParams();
		//TODO size limits etc
	}
	
	public static function addToForm($form, $name, $label, $value, $params)
	{
		$f = $form->addUpload($name, $label);
		if (isset($params['required']) && $params['required'] === TRUE)
			$f->setRequired($params['requiredMessage']);
		if (isset($params['classes']))
			$f->getControlPrototype()->addClass($params['classes']);
	}
	
}