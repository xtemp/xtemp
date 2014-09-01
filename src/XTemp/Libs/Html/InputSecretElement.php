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

	public static function addToForm($form, $name, $label, $value, $params)
	{
		$f = $form->addPassword($name, $label);
		if ($value !== NULL)
			$f->setValue($value);
		if (isset($params['required']) && $params['required'] === TRUE)
			$f->setRequired($params['requiredMessage']);
	}
	
}