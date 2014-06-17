<?php
/*
 * XTemp - XML Templating Engine for PHP
 * FragmentElement.php created on 17. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class FragmentElement extends \XTemp\Tree\Element
{
	private $rendered;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->checkId();
		$this->rendered = $this->useAttr('rendered', 'true');
	}
	
	public function restructureTree()
	{
	}
	
	public function render()
	{
		if ($this->rendered != 'false')
			return $this->renderChildren();
		else
			return '';
	}
	
}