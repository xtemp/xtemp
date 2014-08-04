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
class PanelGridElement extends \XTemp\Tree\Element
{
	private $columns;
	
	protected function loadParams()
	{
		$this->columns = $this->requireAttrPlain('columns');
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		$ret = $this->renderStartElement();
		
		$contents = $this->getChildElements();
		$cnt = count($contents);
		for ($i = 0; $i < $cnt; $i++)
		{
			if ($i == 0)
				$ret .= '<tr>';
			else if ($i % $this->columns == 0)
				$ret .= "</tr>\n<tr>";
			
			$ret .= '<td>' . $contents[$i]->render() . '</td>';
		}
		$ret .= '</tr>';
		
		$ret .= $this->renderEndElement();
		return $ret;
	}

	public function getSimpleName()
	{
		return 'table';
	}
	
	protected function renderAttribute($name)
	{
		if ($name != 'columns')
			return parent::renderAttribute($name);
		else
			return '';
	}
	
}