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
		$this->template = $this->useAttr('template', null);
	}
	
	/**
	 * Sets the nested composition. The nested composition does not load the template by itself; the contents
	 * of the ui:insert elements is obtained from outside.
	 * @param unknown $nested
	 */
	public function setNested($nested)
	{
		$this->nested = $nested;
	}
	
	public function restructureTree()
	{
		if ($this->template)
		{
			$ttree = $this->createTemplateTree($this->template);
			$troot = $ttree->getRoot();
			$params = array();
			
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
				else if ($child instanceof ParamElement)
				{
					$params[] = $child;
				}
			}
			
			$container = new ParametrizedContainer($params);
			$container->addChild($troot);
			$this->getTree()->setRoot($container);
		}
		else
		{
			//no template specified, use the composition as the template root
			$this->tree->setRoot($this);
		}
	}
	
	public function render()
	{
		return $this->renderChildren();
	}
	
	
}