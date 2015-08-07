<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputTextAreaElement.php created on 2. 9. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class InputTextAreaElement extends InputField
{
	
	public static function addToForm($form, $name, $label, $value, $params)
	{
		$f = $form->addTextArea($name, $label);
		if ($value !== NULL)
			$f->setValue($value);
		if (isset($params['required']) && $params['required'] === TRUE)
			$f->setRequired($params['requiredMessage']);
		if (isset($params['classes']))
			$f->getControlPrototype()->addClass($params['classes']);
	}

}