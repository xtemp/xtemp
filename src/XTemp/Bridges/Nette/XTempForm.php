<?php
/*
 * XTemp - XML Templating Engine for PHP
* XTempForm.php created on 31. 7. 2014 by burgetr
*/

namespace XTemp\Bridges\Nette;

use XTemp;
use XTemp\Runtime\IConverter;
use XTemp\ConverterException;
/**
 * A form that remembers the field mapping defined in the template
 *
 * @author burgetr
 */
class XTempForm extends \Nette\Application\UI\Form
{
	protected $mapping;
	protected $params = array();
	
	public function setMapping($mapping)
	{
		$this->mapping = $mapping;
	}
	
	public function getMapping()
	{
		return $this->mapping;
	}
	
	public function getParams($name)
	{
		if (isset($this->params[$name]))
			return $this->params[$name];
		else
			return NULL;
	}
	
	public function setParams($name, $params)
	{
		$this->params[$name] = $params;
	}
	
	public static function constructForm(XTemp\Bridges\Nette\XTempPresenter $presenter, FormDef $def)
	{
		$form = new \XTemp\Bridges\Nette\XTempForm();
		foreach ($def->types as $name => $type)
		{
			$label = isset($def->labels[$name]) ? $def->labels[$name] : '';
			$params = $def->params[$name];
			$val = self::applyConverter($presenter, $params, $def->values[$name]);
			call_user_func("$type::addToForm", $form, $name, $label, $val, $params);
			$form->setParams($name, $params);
		}
		$form->setMapping($def->mappings);
		return $form;
	}
	
	protected static function applyConverter($presenter, $params, $value)
	{
		if (isset($params['converter']))
		{
			$ccls = $params['converter'];
			$conv = new $ccls;
			$cparams = array();
			if (isset($cparams['converter_p']))
				$cparams = json_decode($params['converter_p']);
			if ($conv instanceof IConverter)
				return $conv->getAsString($presenter, $cparams, $value);
			else
				throw new ConverterException("$ccls must implement \XTemp\Runtime\IConverter");
		}
		else
			return $value;
	}
	
}