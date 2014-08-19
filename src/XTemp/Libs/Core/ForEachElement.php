<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ForEachElement.php created on 18. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class ForEachElement extends \XTemp\Tree\Element
{
	private $items;
	private $var;
	private $varStatus;
	
	protected function loadParams()
	{
		$this->items = $this->requireAttrExpr('items');
		$this->var = $this->requireAttrVar('var');
		$this->varStatus = $this->useAttrVar('varStatus', NULL);
	}
	
	public function render()
	{
		$ret = "\n{foreach {$this->items->toPHP()} as {$this->var}}\n";
		if ($this->varStatus !== NULL && $this->varStatus != '$iterator')
			$ret .= "{var {$this->varStatus}=\$iterator}";
		$ret .= $this->renderChildren();
		$ret .= "\n{/foreach}\n";
		return $ret;
	}
	
}