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
	
	protected function loadParams()
	{
		$this->template = $this->requireAttrPlain('template');
	}

	public function restructureTree()
	{
		if ($this->template)
		{
			$ttree = $this->createTemplateTree($this->template);
			$troot = $ttree->getRoot();
			$params = array();
				
			if ($troot)
			{
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
			}
			
			$container = new ParametrizedContainer($params);
			$this->removeAllChildren();
			if ($troot)
			{
				foreach ($troot->getChildren() as $child)
					$container->addChild($child);
			}
			$this->addChild($container);
		}
	}
	
	public function render()
	{
		return $this->renderChildren();
	}
	
}