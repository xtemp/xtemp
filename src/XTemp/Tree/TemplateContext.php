<?php
/*
 * XTemp - XML Templating Engine for PHP
* TemplateContext.php created on 4. 8. 2014 by burgetr
*/

namespace XTemp\Tree;

/**
 * This class represents a rendering context within a rendered template. It holds the local
 * variables and their eventual mapping to presenter properties.
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
	
	/**
	 * Opens a new scope level with the given base mapping string and the mapped local variables.
	 * @param unknown $mapping the base mapping string for the variables in this scope
	 * @param unknown $vars the variables mapping (name=>contents)
	 */
	public function open($mapping, $vars)
	{
		array_push($this->mapStack, $mapping);
		array_push($this->varStack, $vars);
	}
	
	/**
	 * Closes the topmnost scope level.
	 */
	public function close()
	{
		array_pop($this->mapStack);
		array_pop($this->varStack);
	}
	
	/**
	 * Finds and returns a variable in all the scope levels. If the variable is not found
	 * (no local scope contains the variable), the corresponding presenter property is
	 * returned instead.
	 * 
	 * @param unknown $root the variable name
	 * @return unknown the located variable or presenter property
	 */
	public function find($root)
	{
		$prop = $root;
		$idx = '';
		//locate the array index if used
		$p1 = strpos($prop, '[');
		$p2 = strpos($prop, ']');
		if ($p1 !== FALSE && $p2 !== FALSE && $p2 > $p1)
		{
			$idx = substr($prop, $p1+1, $p2 - $p1 - 1);
			$prop = substr($prop, 0, $p1);
		}
			
		//try to find the mapping for the variable in the stack
		$mapping = NULL;
		for ($i = count($this->varStack) - 1; $i >= 0; $i--)
		{
			$map = $this->varStack[$i];
			if (isset($map[$prop]))
			{
				$mapping = $map[$prop];
				break;
			}
		}
		
		$ret = NULL;
		if ($mapping !== NULL)
			$ret = $mapping; //found in local scope, return the value found
		else
			$ret = $this->presenter->$prop; //not found in local scope, try the presenter
		
		if ($idx !== '') //if the index is defined, use it
			$ret = $ret[$idx];
		
		return $ret;
	}
	
	/**
	 * Computes the complete mapping taking into account the local variables.
	 * 
	 * @param unknown $mapping the mapping
	 * @return string|unknown the resulting mapping
	 */
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
