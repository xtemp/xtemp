<?php
/*
 * XTemp - XML Templating Engine for PHP
 * FormElement.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

use XTemp\Bridges\Nette\XTempPresenter;
/**
 *
 * @author      burgetr
 */
class FormElement extends \XTemp\Tree\Element
{
	private static $formSn = 1;
	private $id;
	
	protected function loadParams()
	{
		$this->id = $this->checkIdConstant();
		$this->fname = '_xt_frm_' . (FormElement::$formSn++);
		
	}

	public function beforeRender()
	{
		parent::beforeRender();
		$this->storeFormDef();
	}
	
	public function render()
	{
		$ret = '';
		
		$ret .= "<?php\n";
		$ret .= 'function ' . $this->fname . '($presenter, $form){' . "\n";
		$ret .= '$labels = array();';
		$ret .= $this->recursiveScanLabels($this);
		
		$ret .= 'if (!$form)';
		$ret .= ' $presenter[' . $this->id . '] = $form = new Nette\Application\UI\Form;';
		$ret .= $this->recursiveScanFields($this);
		
		//$ret .= '$form->onSuccess[] = $presenter->_xt_processForm;'; //not necessary here - defined in XTempPresenter
		
		$ret .= '$presenter->storeMapping(\'' . $this->id . '\', array(' . $this->recursiveGetMapping($this) . '));'; 
		
		$ret .= 'return $form;';
		$ret .= "}\n";
		$ret .= $this->fname . '($presenter, isset($presenter[' . $this->id . '])?$presenter[' . $this->id . ']:NULL);' . "\n";
		$ret .= "?>\n";
		
		$ret .= "{form $this->id}\n" . $this->renderChildren() . "\n{/form}";
		return $ret;
	}
	
	//=========================================================================
	
	protected function storeFormDef()
	{
		$code = $this->createFormDef();
		$file = $this->context->presenter->getFormTempFile($this->id);
		file_put_contents($file, $code);
	}
	
	protected function createFormDef()
	{
		$ret = "<?php\n";
		$ret .= 'function _xt_create_form($presenter, $form){' . "\n";
		$ret .= '$labels = array();';
		$ret .= $this->recursiveScanLabels($this);
		
		$ret .= 'if (!$form)';
		$ret .= ' $presenter[' . $this->id . '] = $form = new Nette\Application\UI\Form;';
		$ret .= $this->recursiveScanFields($this);
		
		$ret .= '$presenter->storeMapping(' . $this->id . ', array(' . $this->recursiveGetMapping($this) . '));';
		
		$ret .= 'return $form;';
		$ret .= "}\n";
		$ret .= $this->fname . '($presenter, isset($presenter[' . $this->id . '])?$presenter[' . $this->id . ']:NULL);' . "\n";
		$ret .= "?>\n";
		
		return $ret;
	}
	
	private function recursiveScanLabels($root)
	{
		if ($root instanceof OutputLabelElement)
		{
			return '$labels[' . $root->getFor() . '] = ' . $root->getValue() . ";\n";
		}
		else if ($root instanceof InputField)
		{
			return '';
		}
		else
		{
			$ret = '';
			foreach ($root->getChildren() as $child)
				$ret .= $this->recursiveScanLabels($child);
			return $ret;
		}
	}

	private function recursiveScanFields($root)
	{
		if ($root instanceof OutputLabelElement)
		{
			return '';
		}
		else if ($root instanceof InputField)
		{
			return '$form->' . $root->getFnCall() . ";\n";
		}
		else
		{
			$ret = '';
			foreach ($root->getChildren() as $child)
				$ret .= $this->recursiveScanFields($child);
			return $ret;
		}
	}
	
	private function recursiveGetMapping($root)
	{
		if ($root instanceof InputField)
		{
			$id = $root->getId();
			$expr = $root->getValue();
			if ($expr && $expr->isLValue())
			{
				return "$id=>'" . implode(':', $expr->getLValueIdentifiers()) . "',";
			}
			return '';
		}
		else
		{
			$ret = '';
			foreach ($root->getChildren() as $child)
				$ret .= $this->recursiveGetMapping($child);
			return $ret;
		}
	}
	

}