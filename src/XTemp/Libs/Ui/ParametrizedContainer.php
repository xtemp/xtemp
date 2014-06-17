<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ParametrizedContainer.php created on 17. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class ParametrizedContainer extends \XTemp\Tree\Component
{
	private $params;
	
	public function __construct($params)
	{
		parent::__construct();
		$this->params = $params;
	}

	public function render()
	{
		$ret = '';
		foreach ($this->params as $param)
			$ret .= $param->render();
		$ret .= $this->renderChildren();
		return $ret;
	}
	
}