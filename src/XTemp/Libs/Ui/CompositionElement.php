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
class CompositionElement extends CompoundElementBase
{
	private $template;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
	}
	
	public function restructureTree()
	{
		$this->template = $this->requireAttr('template');
		$ttree = $this->createTemplateTree($this->template);
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
		
		$this->getTree()->setRoot($troot);
	}
	
	public function render()
	{
		return $this->getTree()->getFile();
	}
	
	
}