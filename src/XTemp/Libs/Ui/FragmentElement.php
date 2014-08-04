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
	
	protected function loadParams()
	{
		$this->checkId();
		$this->rendered = $this->useAttrExpr('rendered', 'true');
	}
	
	public function restructureTree()
	{
	}
	
	public function render()
	{
		return $this->renderNotIf($this->rendered->toPHP(), 'false', $this->renderChildren());
	}
	
}