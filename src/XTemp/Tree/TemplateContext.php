<?php
/*
 * XTemp - XML Templating Engine for PHP
* TemplateContext.php created on 4. 8. 2014 by burgetr
*/

namespace XTemp\Tree;

/**
 * 
 * @author      burgetr
 */
class TemplateContext
{
	protected $presenter;
	
	protected $mapStack;
	protected $varStack;
	
	
	public function __construct($presenter)
	{
		$this->presenter = $presenter;
		$this->mapStack = array();
		$this->varStack = array();
	}
	
	public function open($mapping, $vars)
	{
		array_push($this->mapStack, $mapping);
		array_push($this->varStack, $vars);
	}
	
	public function close()
	{
		array_pop($this->mapStack);
		array_pop($this->varStack);
	}
	
	public function find($root)
	{
		//try to find the mapping for the variable in the stack
		$mapping = NULL;
		for ($i = count($this->varStack) - 1; $i >= 0; $i--)
		{
			$map = $this->varStack[$i];
			if (isset($map[$root]))
			{
				$mapping = $map[$root];
				break;
			}
		}
		if ($mapping !== NULL)
			return $mapping;
		else
			return $this->presenter->$root;
	}
	
	public function map($mapping)
	{
		$ctxmap = NULL;
		//find the root variable in context vatiables
		$mapids = explode(':', $mapping);
		if (count($mapids) > 0)
		{
			$root = $mapids[0];
			for ($i = count($this->varStack) - 1; $i >= 0; $i--)
			{
				$vars = $this->varStack[$i];
				if (isset($vars[$root]))
				{
					$ctxmap = $this->mapStack[$i];
					break;
				}
			}
		}
		
		if ($ctxmap !== NULL)
			return $ctxmap . ':' . implode(':', array_slice($mapids, 1));
		else
			return $mapping;
		
	}
	
}
