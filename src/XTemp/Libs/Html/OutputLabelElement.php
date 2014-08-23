<?php
/*
 * XTemp - XML Templating Engine for PHP
 * OutputLabelElement.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class OutputLabelElement extends FormField
{
	private $for;
	private $value;
	
	private $partial = FALSE;
	
	protected function loadParams()
	{
		$this->for = $this->requireAttrExpr('for');
		$this->value = $this->requireAttrExpr('value');
	}

	public function getFor()
	{
		return $this->for->toPHP();
	}
	
	public function getValue()
	{
		return $this->value->toPHP();
	}
	
	public function setPartial($bool)
	{
		$this->partial = $bool;
	}
	
	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		if ($this->form->inPhpMode())
			return $this->renderDeclaration();
		else
		{
			if ($this->partial)
				return '{label ' . $this->getFor() . ':}{= ' . $this->getValue() . '}{/label}';
			else
				return '{label ' . $this->getFor() . '}{= ' . $this->getValue() . '}{/label}';
		}
	}
	
	protected function renderDeclaration()
	{
		$ret = "<?php ";
		$ret .= '$presenter->addFormLabel('
				. $this->for->toPHP() . ',' . $this->value->toPHP() . ');';
		$ret .= "?>\n";
		return $ret;
	}
	
}