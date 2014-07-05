<?php
/*
 * XTemp - XML Templating Engine for PHP
 * IncludeElement.php created on 17. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class IncludeElement extends CompoundElementBase
{
	private $src;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->src = $this->requireAttrPlain('src');
	}

	public function restructureTree()
	{
		if ($this->src)
		{
			$ttree = $this->createTemplateTree($this->src);
			$troot = $ttree->getRoot();
			$params = array();
	
			//coolect the parametres (if any)
			foreach ($this->getChildren() as $child)
			{
				if ($child instanceof ParamElement)
				{
					$params[] = $child;
				}
			}
				
			$this->removeAllChildren();
			$container = new ParametrizedContainer($params);
			$container->addChild($troot);
			$this->addChild($container);
		}
	}
	
	public function render()
	{
		return $this->renderChildren();
	}
	
}