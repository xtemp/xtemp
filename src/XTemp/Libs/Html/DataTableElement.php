<?php
/*
 * XTemp - XML Templating Engine for PHP
 * DataTable.php created on 11. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class DataTableElement extends \XTemp\Tree\Element
{
	private $value;
	private $varname;
	
	private $columns;
	private $other;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->value = $this->requireAttrExpr('value');
		$this->varname = $this->requireAttrVar('var');
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
		$cls = $this->translateExpr("table table-striped sortable");
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
		$ret .= '{foreach ' . $this->value . ' as ' . $this->varname . "}\n";
		$ret .= '<tr>';
		foreach ($this->columns as $col)
		{
			$ret .= '<td>' . $col->render() . '</td>';
		}
		$ret .= "</tr>\n";
		$ret .= "{/foreach}\n";
		$ret .= "</tbody>\n";
		
		$ret .= $this->renderEndElement();
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