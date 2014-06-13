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
	
	public function beforeRender()
	{
		$ttree = $this->createTemplateTree();
		$troot = $ttree->getRoot();
		
		//find the ui:define tags and match them
		foreach ($this->getChildren() as $child)
		{
			if ($child instanceof DefineElement)
			{
				$name = $child->getName();
				$ins = $this->findInsert($name, $troot);
				if ($ins)
				{
					$ins->removeAllChildren();
					$ins->addAll($child->getChildren());
				}
			}
		}
		
		$this->tree->setRoot($troot);
	}
	
	public function render()
	{
		return $this->tree->getFile();
	}
	
	//=========================================================================
	
	private function createTemplateTree()
	{
		//load the referenced template
		$file = dirname($this->tree->getFile()) . '/' . $this->template;
		if (!is_file($file)) {
			throw new \RuntimeException("Missing template file '$file' referenced in '" . $this->tree->getFile() . "'");
		}
		$src = file_get_contents($file);
		//create a new tree from the template
		$filter = new \XTemp\Filter();
		$tempTree = $filter->buildTree($src, $file);
		return $tempTree;
	}
	
	private function findInsert($name, $root)
	{
		if ($root instanceof InsertElement && $root->getName() == $name)
		{
			return $root;
		}
		else
		{
			foreach ($root->getChildren() as $child)
			{
				if (($ret = $this->findInsert($name, $child)) != NULL)
					return $ret;
			}
		}
		return NULL;
	}
	
}