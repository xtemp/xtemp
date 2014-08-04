<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Element.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class IfElement extends \XTemp\Tree\Element
{
	private $cond;
	
	protected function loadParams()
	{
		$this->cond = $this->requireAttrExpr('test');
	}
	
	public function render()
	{
		return "\n{if {$this->cond->toPHP()}}\n" . $this->renderChildren() . "\n{/if}\n";
	}
	
}