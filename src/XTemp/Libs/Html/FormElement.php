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

	protected $phpRenderMode = FALSE;
	
	
	protected function loadParams()
	{
		$this->id = $this->checkIdConstant();
		$this->fname = '_xt_frm_' . (FormElement::$formSn++);
		
	}

	public function beforeRender()
	{
		parent::beforeRender();
		$this->recursiveAssignFields($this);
		//$this->storeFormDef();
	}
	
	public function render()
	{
		$ret = '';
		
		//create the form definition code
		$this->phpRenderMode = TRUE;
		$ret .= "<?php\n";
		$ret .= '$presenter->startFormRendering(\'' . $this->id . "');\n";
		$ret .= "?>\n";
		
		$ret .= $this->renderChildren();
		
		$ret .= "<?php\n";
		$ret .= '$presenter->finishFormRendering();' . "\n";
		$ret .= "?>\n";
		
		//create the form rendering code
		$this->phpRenderMode = FALSE;
		$ret .= "{form $this->id}\n" . $this->renderChildren() . "\n{/form}";
		return $ret;
	}
	
	public function inPhpMode()
	{
		return $this->phpRenderMode;
	}
	
	//=========================================================================
	
	private function recursiveAssignFields($root)
	{
		if ($root instanceof FormField)
			$root->setForm($this);
		foreach ($root->getChildren() as $child)
			$this->recursiveAssignFields($child);
	}
	
	
	protected function storeFormDef()
	{
		$code = $this->createFormDef();
		$file = $this->context->presenter->getFormTempFile($this->id);
		file_put_contents($file, $code);
	}
	
	protected function createFormDef()
	{
		$ret = "<?php\n";
		$ret .= 'function _xt_create_form($presenter){' . "\n";
		$ret .= '$labels = array();';
		$ret .= $this->recursiveScanLabels($this);
		
		$ret .= '$form = new \XTemp\Bridges\Nette\XTempForm;';
		$ret .= $this->recursiveScanFields($this);
		
		$ret .= '$presenter->storeMapping(\'' . $this->id . '\', array(' . $this->recursiveGetMapping($this) . '));';
		
		$ret .= 'return $form;';
		$ret .= "}\n";
		
		return $ret;
	}
	
	private function recursiveScanLabels($root)
	{
		if ($root instanceof OutputLabelElement)
		{
			$this->labels[$root->getFor()] = $root;
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
			$id = $root->getId();
			if (isset($this->labels[$id]))
				$root->setLabel($this->labels[$id]);
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
			$expr = $root->getMappingValue();
			if ($expr !== NULL)
			{
				return "$id=>'$expr',";
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