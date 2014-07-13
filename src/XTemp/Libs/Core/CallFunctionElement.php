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
	private $script;

	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->name = $this->useAttrExpr('name', NULL);
	}
	
	public function beforeRender()
	{
		$this->params = array();
		$this->script = NULL;
		foreach ($this->getChildren() as $child)
		{
			if ($child instanceof ParamElement)
				$this->params[] = $child;
			else if ($child instanceof \XTemp\Libs\XHTML\Element
						&& $child->getElementName() == "script")
				$this->script = $child;
		}
	}
	
	public function render()
	{
		$ret = '<script type="text/javascript">' . "\n";
		
		//generate the function definition
		if ($this->name !== NULL)
		{
			$prolog = '{= ' . $this->name . '|noescape}'; //call by name
			$epilog = '';
		}
		else if ($this->script !== NULL)//anonymous function
		{
			$prolog = '(function(' . $this->getParamNames() . '){';
			$prolog .= $this->script->renderChildren();
			$prolog .= '}';
			$epilog = ')';
		}
		else
			throw \XTemp\MissingAttributeException("No function name nor script element defined for callFunction");			
			
		//call the function
		$ret .= "$prolog(" . $this->getParamValues() . ")$epilog;\n";
		$ret .= "</script>\n";
		return $ret;
	}

	private function getParamNames()
	{
		$ret = "";
		$first = true;
		foreach ($this->params as $param)
		{
			if ($param->getName() === NULL)
				throw \XTemp\MissingAttributeException("Anonymous function calls must declare names for all params.");
			if (!$first) $ret .= ",";
			$ret .= $param->getName();
			$first = false;
		}
		return $ret;
	}
	
	private function getParamValues()
	{
		$ret = "";
		$first = true;
		foreach ($this->params as $param)
		{
			if (!$first) $ret .= ",";
			$ret .= $param->getValue();
			$first = false;
		}
		return $ret;
	}
	
}