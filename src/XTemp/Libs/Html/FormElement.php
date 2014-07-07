<?php
/*
 * XTemp - XML Templating Engine for PHP
 * FormElement.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class FormElement extends \XTemp\Tree\Element
{
	private static $formSn = 1;
	private $id;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->id = $this->checkId();
		$this->fname = '_xt_frm_' . (FormElement::$formSn++);
		
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		$ret = '';
		
		$ret .= "<?php\n";
		$ret .= 'function ' . $this->fname . "(){\n";
		$ret .= '$labels = array();';
		$ret .= $this->recursiveScanLabels($this);
		
		$ret .= '$form = new Nette\Application\UI\Form;';
		$ret .= $this->recursiveScanFields($this);
		
		$ret .= 'return $form;';
		$ret .= "}\n";
		$ret .= '$_control[' . $this->id . '] = ' . $this->fname . "();\n";
		$ret .= "?>\n";
		
		$ret .= "{form $this->id}\n" . $this->renderChildren() . "\n{/form}";
		return $ret;
	}
	
	//=========================================================================
	
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

}