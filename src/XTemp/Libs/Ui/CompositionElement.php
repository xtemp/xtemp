<?php
/*
 * XTemp - XML Templating Engine for PHP
 * CompositionElement.php created on 13. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author      burgetr
 */
class CompositionElement extends \XTemp\Tree\Element
{
	private $template;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->template = $this->requireAttr('template');
	}
	
	public function render()
	{
		return $this->tree->getFile();
	}
	
	/*public function addToTree($tree, $parent)
	{
		$this->tree = $tree;
		
		//load the referenced template
		$file = dirname($this->tree->getFile()) . '/' . $this->template;
		if (!is_file($file)) {
			throw new \RuntimeException("Missing template file '$file' referenced in '" . $this->tree->getFile() . "'");
		}
		$src = file_get_contents($file);
		//create a new tree from the template
		$filter = new \XTemp\Filter();
		$tempTree = $filter->buildTree($src, $file);
		//make the template tree the main tree
		if ($parent) 
			$parent->addChild($tempTree->getRoot());
		else
			$tree->setRoot($tempTree->getRoot());
		//$tree->setRoot($this);
	}*/

	
}