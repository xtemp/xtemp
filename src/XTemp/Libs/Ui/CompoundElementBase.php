<?php
/*
 * XTemp - XML Templating Engine for PHP
 * CompoundElementBase.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 * A base of a generic element that loads a component tree from external files. 
 * 
 * @author burgetr
 */
abstract class CompoundElementBase extends \XTemp\Tree\Element
{
	
	protected function createTemplateTree($templateName)
	{
		$file = dirname($this->getTree()->getFile()) . '/' . $templateName;
		return $this->loadExternalTemplate($file);
	}
	
	protected function findInsert($name, $root)
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
	
	protected function findComposition($root)
	{
		if ($root instanceof CompositionElement)
		{
			return $root;
		}
		else
		{
			foreach ($root->getChildren() as $child)
			{
				if (($ret = $this->findComposition($child)) != NULL)
					return $ret;
			}
		}
		return NULL;
	}
	
}