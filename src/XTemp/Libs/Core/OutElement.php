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
	
	private $convClass = NULL;
	private $convParams = NULL;
	
	protected function loadParams()
	{
		$this->value = $this->requireAttrExpr('value');
		$this->escape = $this->useAttrPlain('escapeXml', "true", array('true', 'false'));
		$this->filter = $this->useAttrPlain('filter', NULL);
	}
	
	public function addControlParam($name, $value)
	{
		if ($name === "converter")
			$this->convClass = $value;
		else if ($name === "converter_p")
			$this->convParams = $value;
	}
	
	public function render()
	{
		$f = $this->filter === NULL ? '' : ('|' . $this->filter);
		
		$v = $this->value->toPHP();
		if ($this->convClass !== NULL)
		{
			$v = '(new ' . $this->convClass . ')->getAsString($presenter,' . var_export($this->convParams, TRUE) . ',' . $v . ')';
		}
		
		if ($this->escape == "false")
			return "{= " . $v . "|noescape$f}";
		else
			return "{= " . $v . "$f}";
	}
	
}