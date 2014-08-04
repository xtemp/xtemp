<?php
/*
 * XTemp - XML Templating Engine for PHP
 * OutElement.php created on 10. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class OutElement extends \XTemp\Tree\Element
{
	private $value;
	private $escape;
	
	protected function loadParams()
	{
		$this->value = $this->requireAttrExpr('value');
		$this->escape = $this->useAttrPlain('escapeXml', "true", array('true', 'false'));
	}
	
	public function render()
	{
		if ($this->escape == "false")
			return "{= " . $this->value->toPHP() . "|noescape}";
		else
			return "{= " . $this->value->toPHP() . "}";
	}
	
}