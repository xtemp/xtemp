<?php
/*
 * XTemp - XML Templating Engine for PHP
 * MessageElement.php created on 6. 9. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

use XTemp\Tree\Expression;

/**
 *
 * @author      burgetr
 */
class MessageElement extends FormField
{
	private $for;
	
	protected function loadParams()
	{
		parent::loadParams();
		$this->for = $this->requireAttrExpr('for');
	}
	
	public function render()
	{
		if ($this->form->inPhpMode())
			return '';
		else
		{
			$ret = "\n" . '{if isset($form[' . $this->for->toPHP() . "]->error)}\n";
			$ret .= $this->renderStartElement();
			$ret .= '{= $form['. $this->for->toPHP() . "]->error}";
			$ret .= $this->renderEndElement();
			$ret .= "\n{/if}\n";
			return $ret;
		}		
	}
	
	public function getSimpleName()
	{
		return 'span';
	}
	
	protected function renderAttribute($name)
	{
		if ($name != 'for')
			return parent::renderAttribute($name);
		else
			return '';
	}
	
	
}