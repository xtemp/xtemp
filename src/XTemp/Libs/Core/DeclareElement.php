<?php
/*
 * XTemp - XML Templating Engine for PHP
 * DeclareElement.php created on 12. 6. 2015 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class DeclareElement extends \XTemp\Tree\Element
{
	private $value;
	private $escape;
	private $filter;
	
	private $convClass = NULL;
	private $convParams = NULL;
	
	protected function loadParams()
	{
		$this->var = $this->requireAttrVar('var');
		$this->value = $this->requireAttrExpr('value');
	}
	
	public function render()
	{
		$n = $this->var;
		$v = $this->value->toPHP();
		
		return '{var ' . $n . '=' . $v . '}';
	}
	
}