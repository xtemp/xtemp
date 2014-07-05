<?php
/*
* XTemp - XML Templating Engine for PHP
* CallFunctionElement.php created on 2. 7. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

/**
*
* @author      burgetr
*/
class CallFunctionElement extends \XTemp\Tree\Element
{
	private $name;
	private $params;

	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->name = $this->requireAttrExpr('name');
	}
	
	public function beforeRender()
	{
		$this->params = array();
		foreach ($this->getChildren() as $child)
		{
			if ($child instanceof ParamElement)
				$this->params[] = $child;
		}
	}
	
	public function render()
	{
		$ret = '<script type="text/javascript">' . "\n";
		$ret .= '{= ' . $this->name . '|noescape}(';
		$first = true;
		foreach ($this->params as $param)
		{
			if (!$first) $ret .= ",";
			$ret .= $param->getValue();
			$first = false;
		}
		$ret .= ");\n</script>";
		return $ret;
	}

}