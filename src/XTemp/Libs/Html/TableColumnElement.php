<?php
/*
 * XTemp - XML Templating Engine for PHP
 * TableColumnElement.php created on 11. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class TableColumnElement extends \XTemp\Tree\Element
{
	private $headerText;
	
	protected function loadParams()
	{
		$this->headerText = $this->useAttrExpr('headerText', '');
	}
	
	public function render()
	{
		return $this->renderChildren();
	}
	
	public function getHeaderText()
	{
		return $this->headerText->toPHP();
	}
	
}