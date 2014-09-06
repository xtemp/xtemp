<?php
/*
* XTemp - XML Templating Engine for PHP
* ConverterElement.php created on 6. 9. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

use XTemp\Libs\Html\InputField;
/**
*
* @author      burgetr
*/
class ConverterElement extends \XTemp\Tree\Element
{
	private $converterClass;
	private $decPoint;
	private $thousandsSep;

	protected function loadParams()
	{
		$this->converterClass = $this->requireAttrPlain('converterClass');
		if (!class_exists($this->converterClass))
			throw new \XTemp\InvalidAttributeValueException("Converter class {$this->converterClass} does not exist");
	}
	
	public function beforeRender()
	{
		$p = $this->getParent();
		if ($p !== NULL)
		{
			$p->addControlParam("converter", $this->converterClass);
			$parms = array();
			foreach ($this->attributes as $name => $value)
			{
				if ($name != 'converterClass')
					$parms[$name] = $this->useAttrPlain($name, NULL);
			}
			$p->addControlParam("converter_p", $parms);
		}
	}
	
	public function render()
	{
		return '';
	}

}