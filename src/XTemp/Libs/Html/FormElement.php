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
	private $id;
	
	private $labels = array();
	private $fields = array();
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->id = $this->checkId();
	}

	public function beforeRender()
	{
		parent::beforeRender();
		$this->recursiveScanChildren($this);
	}
	
	public function render()
	{
		return "{form $this->id}\n" . $this->renderChildren() . "\n{/form}";
	}
	
	//=========================================================================
	
	private function recursiveScanChildren($root)
	{
		if ($root instanceof OutputLabelElement)
			$this->labels[$root->getFor()] = $root; //TODO this should generate a PHP code
		else if ($root instanceof InputField)
			$this->fields[$root->getId()] = $root;
		else
		{
			foreach ($root->getChildren() as $child)
				$this->recursiveScanChildren($child);
		}
	}

}