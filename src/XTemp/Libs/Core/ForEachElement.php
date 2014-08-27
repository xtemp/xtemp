<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ForEachElement.php created on 18. 8. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author      burgetr
 */
class ForEachElement extends \XTemp\Tree\Element
{
	protected static $keyindex = 1;
	
	private $value;
	private $varname;
	private $varStatus;
	private $varIndex;
	
	protected function loadParams()
	{
		$this->value = $this->requireAttrExpr('items');
		$this->varname = $this->requireAttrVar('var');
		$this->varStatus = $this->useAttrVar('varStatus', NULL);
		$this->varIndex = $this->useAttrVar('varIndex', NULL);
	}
	
	public function render()
	{
		$ret = '';
	
		$doMapping = $this->value->isLValue();
		$ret .= $this->renderIterationStart($doMapping);
		$ret .= $this->renderChildren();
		$ret .= $this->renderIterationEnd($doMapping);
		return $ret;
	}
	
	protected function renderIterationStart($doMapping)
	{
		$ret = '';
		if ($doMapping)
		{
			$keyname = '$_xt_fe_key' . (self::$keyindex++);
			$ctrlname = '$_xt_fe_ctrl' . (self::$keyindex++);
			$mapping = $this->value->getLValueIdString() . "[$keyname]";
				
			$ret .= '{var ' . $ctrlname . '=' . $this->value->toPHP() . '}';
			$ret .= '{foreach ' . $ctrlname . ' as ' . $keyname . '=>' . $this->varname . "}\n";
			$ret .= '{? $_xt_ctx->open("' . $mapping . '", array(\'' . substr($this->varname, 1) . '\'=>' . $ctrlname . '[' . $keyname . ']))}';
		}
		else
		{
			$ret .= '{foreach ' . $this->value->toPHP() . ' as ' . $this->varname . "}\n";
		}
		if ($this->varStatus !== NULL && $this->varStatus != '$iterator')
			$ret .= "{var {$this->varStatus}=\$iterator}";
		if ($this->varIndex !== NULL && $this->varIndex != '$iterations')
			$ret .= "{var {$this->varIndex}=\$iterations}";
		return $ret;
	}
	
	protected function renderIterationEnd($doMapping)
	{
		$ret = '';
		if ($doMapping)
			$ret .= '{? $_xt_ctx->close()}';
		$ret .= "{/foreach}\n";
		return $ret;
	}
	
	
}