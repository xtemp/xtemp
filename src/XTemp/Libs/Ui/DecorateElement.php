<?php
/*
 * XTemp - XML Templating Engine for PHP
 * DecorationElement.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class DecorateElement extends \XTemp\Libs\Ui\CompoundElementBase
{
	private $template;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->template = $this->requireAttr('template');
	}

	public function beforeRender()
	{
		$ttree = $this->createTemplateTree($this->template);
		$troot = $ttree->getRoot();
	
		$comp = $this->findComposition($troot);
		if ($comp)
		{
			//find the ui:define tags and match them
			foreach ($this->getChildren() as $child)
			{
				if ($child instanceof DefineElement)
				{
					$name = $child->getName();
					$ins = $this->findInsert($name, $comp);
					if ($ins)
					{
						echo "$name found";
						$ins->removeAllChildren();
						$ins->addAll($child->getChildren());
					}
					else
						echo "$name not found";
						
				}
			}
		}
		
		$this->removeAllChildren();
		if ($comp)
		{
			foreach ($comp->getChildren() as $child)
			{
				$this->addChild($child);
				echo "add " . get_class($child) . "<br>";
			}
		}
	}
	
	public function render()
	{
		$this->renderChildren();
	}
	
}