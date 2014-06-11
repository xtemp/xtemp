<?php
/*
 * XTemp - XML Templating Engine for PHP
 * TableColumnElement.php created on 11. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

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
		$this->headerText = $this->requireAttr('headerText');
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