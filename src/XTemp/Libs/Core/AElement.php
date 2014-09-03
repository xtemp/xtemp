<?php
/*
 * XTemp - XML Templating Engine for PHP
 * AElement.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class AElement extends \XTemp\Tree\Element
{
	private $href;
	private $params;
	
	protected function loadParams()
	{
		$this->href = $this->requireAttrExpr('href');
		$this->params = $this->useAttrExpr('params', NULL);
	}
	
	public function render()
	{
		return $this->renderStartElement() . $this->renderChildren() . $this->renderEndElement();
	}

	protected function renderAttribute($name)
	{
		if ($name == 'href')
		{
			$p = '';
			if ($this->params !== NULL)
				$p = ", (expand) array" . $this->params->toPHP();
			return 'href="{link ' . $this->href->toPHP() . $p . '}"';
		}
		else if ($name != 'params')
			return parent::renderAttribute($name); 
	}
	
}