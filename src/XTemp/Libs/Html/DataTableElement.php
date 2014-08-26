<?php
/*
 * XTemp - XML Templating Engine for PHP
 * DataTable.php created on 11. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

use XTemp\Tree\Expression;
/**
 *
 * @author      burgetr
 */
class DataTableElement extends \XTemp\Tree\Element
{
	protected static $keyindex = 1;
	
	private $value;
	private $varname;
	private $varStatus;
	
	private $columns;
	private $other;
	
	protected function loadParams()
	{
		$this->value = $this->requireAttrExpr('value');
		$this->varname = $this->requireAttrVar('var');
		$this->varStatus = $this->useAttrVar('varStatus', NULL);
	}

	public function beforeRender()
	{
		//sort columns
		$this->columns = array();
		$this->other = array();
		foreach ($this->getChildren() as $child)
		{
			if ($child instanceof TableColumnElement)
				$this->columns[] = $child;
			else
				$this->other[] = $child;
		}
		//set attributes for rendering
		$cls = Expression::translate("table table-striped sortable");
		if (isset($this->attributes['class']))
			$this->attributes['class'] = $cls . '." ".' . $this->attributes['class'];
		else
			$this->attributes['class'] = $cls;
	}
	
	public function render()
	{
		$ret = $this->renderStartElement();
		
		//first render everything but columns (captions etc)
		foreach ($this->other as $comp)
			$ret .= $comp->render();
		
		//header
		$ret .= "<thead><tr>";
		foreach ($this->columns as $col)
		{
			$ret .= '<th>{= ' . $col->getHeaderText() . '}</th>';
		}
		$ret .= "</tr></thead>\n";
		
		//the columns
		$ret .= "<tbody>\n";
		
		$doMapping = $this->value->isLValue();
		$ret .= $this->renderIterationStart($doMapping);
		$ret .= '<tr>';
		foreach ($this->columns as $col)
		{
			$ret .= '<td>' . $col->render() . '</td>';
		}
		$ret .= "</tr>\n";
		$ret .= $this->renderIterationEnd($doMapping);
		
		$ret .= "</tbody>\n";
		
		$ret .= $this->renderEndElement();
		return $ret;
	}

	protected function renderIterationStart($doMapping)
	{
		$ret = '';
		if ($doMapping)
		{
			$keyname = '$_xt_dt_key' . (self::$keyindex++);
			$ctrlname = '$_xt_dt_ctrl' . (self::$keyindex++);
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
	
	public function getSimpleName()
	{
		return 'table';
	}
	
	protected function renderAttribute($name)
	{
		if ($name != 'value' && $name != 'var')
			return parent::renderAttribute($name);
		else
			return '';
	}
	
}