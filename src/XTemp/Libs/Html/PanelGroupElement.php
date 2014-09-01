<?php
/*
 * XTemp - XML Templating Engine for PHP
 * PanelGroupElement.php created on 1. 9. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author burgetr
 */
class PanelGroupElement extends \XTemp\Tree\Element
{
	private $rendered;
	private $layout;
	
	protected function loadParams()
	{
		$this->checkId();
		$this->rendered = $this->useAttrExpr('rendered', 'true');
		$this->layout = $this->useAttrPlain('layout', NULL);
	}
	
	public function render()
	{
		$ret = '';
		if ($this->layout === 'block')
			$ret .= "<div>";
		$ret .= $this->renderNotIf($this->rendered->toPHP(), 'false', $this->renderChildren());
		if ($this->layout === 'block')
			$ret .= "</div>";
		return $ret;
	}
	
}