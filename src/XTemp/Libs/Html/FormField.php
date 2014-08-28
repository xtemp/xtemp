<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputField.php created on 21. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
abstract class FormField extends \XTemp\Tree\Element
{
	protected $form;
	
	public function getForm()
	{
		return $this->form;
	}
	
	public function setForm(FormElement $form)
	{
		$this->form = $form;
	}
	
}