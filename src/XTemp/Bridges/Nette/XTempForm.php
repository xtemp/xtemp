<?php
/*
 * XTemp - XML Templating Engine for PHP
* XTempForm.php created on 31. 7. 2014 by burgetr
*/

namespace XTemp\Bridges\Nette;

use XTemp;
/**
 * A form that remembers the field mapping defined in the template
 *
 * @author burgetr
 */
class XTempForm extends \Nette\Application\UI\Form
{
	protected $mapping;
	
	public function setMapping($mapping)
	{
		$this->mapping = $mapping;
	}
	
	public function getMapping()
	{
		return $this->mapping;
	}
	
	
	public static function constructForm(XTemp\Bridges\Nette\XTempPresenter $presenter, FormDef $def)
	{
		$form = new \XTemp\Bridges\Nette\XTempForm();
		foreach ($def->types as $name => $type)
		{
			$label = isset($def->labels[$name]) ? $def->labels[$name] : '';
			call_user_func("$type::addToForm", $form, $name, $label, $def->values[$name], $def->params[$name]);
		}
		$form->setMapping($def->mappings);
		return $form;
	}
	
}