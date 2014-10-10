<?php
/*
 * XTemp - XML Templating Engine for PHP
 * OutputStylesheetElement.php created on 10. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class OutputStylesheetElement extends \XTemp\Tree\Element
{
	private $name;
	
	protected function loadParams()
	{
		$this->name = $this->requireAttrExpr('name');
	}
	
	public function render()
	{
		return '<link rel="stylesheet" type="text/css" href="{$basePath}/{= ' . $this->name->toPHP() . '}">'; 
	}
	
}