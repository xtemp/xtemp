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
	private $filter;
	
	protected function loadParams()
	{
		$this->value = $this->requireAttrExpr('value');
		$this->escape = $this->useAttrPlain('escapeXml', "true", array('true', 'false'));
		$this->filter = $this->useAttrPlain('filter', NULL);
	}
	
	public function render()
	{
		$f = $this->filter === NULL ? '' : ('|' . $this->filter);
		
		if ($this->escape == "false")
			return "{= " . $this->value->toPHP() . "|noescape$f}";
		else
			return "{= " . $this->value->toPHP() . "$f}";
	}
	
}