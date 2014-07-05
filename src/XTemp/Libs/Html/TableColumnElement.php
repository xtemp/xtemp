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
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->headerText = $this->requireAttrExpr('headerText');
	}
	
	public function render()
	{
		return $this->renderChildren();
	}
	
	public function getHeaderText()
	{
		return $this->headerText;
	}
	
}