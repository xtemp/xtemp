<?php
/*
 * XTemp - XML Templating Engine for PHP
 * MessagesElement.php created on 6. 9. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

use XTemp\Tree\Expression;

/**
 *
 * @author      burgetr
 */
class MessagesElement extends FormField
{
	private $layout;
	
	protected function loadParams()
	{
		parent::loadParams();
		$this->layout = $this->useAttrPlain('layout', 'list', array('table', 'list'));
	}
	
	public function render()
	{
		if ($this->form->inPhpMode())
			return '';
		else
		{
			if (!isset($this->attributes['class']))
				$this->attributes['class'] = Expression::translate("errors");
			$ret = '';
			$ret .= "\n" . '{if $form->hasErrors()}' . "\n"; 
			$ret .= $this->renderStartElement();
			if ($this->layout == 'table')
			{
				$ret .= '<tr n:foreach="$form->errors as $error"><td>{$error}</td></tr>';
			}
			else
			{
				$ret .= '<li n:foreach="$form->errors as $error">{$error}</li>';
			}
			$ret .= $this->renderEndElement();
			$ret .= "\n{/if}\n";
			return $ret;
		}		
	}
	
	public function getSimpleName()
	{
		return ($this->layout == 'table') ? 'table' : 'ul';
	}
	
	protected function renderAttribute($name)
	{
		if ($name != 'layout')
			return parent::renderAttribute($name);
		else
			return '';
	}
	
	
}