<?php
/*
* XTemp - XML Templating Engine for PHP
* ConvertNumberElement.php created on 31. 8. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

use XTemp\Libs\Html\InputField;
/**
*
* @author      burgetr
*/
class ConvertNumberElement extends \XTemp\Tree\Element
{
	private $decimals;
	private $decPoint;
	private $thousandsSep;

	protected function loadParams()
	{
		$this->decimals = $this->useAttrPlain('decimals', NULL);
		$this->decPoint = $this->useAttrPlain('decPoint', NULL);
		$this->thousandsSep = $this->useAttrPlain('thousandsSep', NULL);
	}
	
	public function beforeRender()
	{
		$p = $this->getParent();
		if ($p !== NULL)
		{
			$p->addControlParam("converter", "\XTemp\Runtime\NumberConverter");
			$parms = array();
			if ($this->decimals !== NULL) $parms['decimals'] = $this->decimals;
			if ($this->decPoint !== NULL) $parms['decPoint'] = $this->decimals;
			if ($this->thousandsSep !== NULL) $parms['thousandsSep'] = $this->thousandsSep;
			$p->addControlParam("converter_p", $parms);
		}
	}
	
	public function render()
	{
		return '';
	}

}