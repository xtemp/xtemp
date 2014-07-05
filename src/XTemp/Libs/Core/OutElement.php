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
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->value = $this->requireAttrExpr('value');
		$this->escape = $this->useAttrPlain('escapeXml', "true", array('true', 'false'));
	}
	
	public function render()
	{
		if ($this->escape == "false")
			return "{= " . $this->value . "|noescape}";
		else
			return "{= " . $this->value . "}";
	}
	
}