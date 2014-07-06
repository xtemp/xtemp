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
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		return '{input ' . $this->id . '}';
	}
}