<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Component.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 * A generic component that represents a single node in the component tree.
 *
 * @author      burgetr
 */
abstract class Component
{
	/** @var XTemp\Tree\ComponentTree */
	protected $tree;
	
	/** @var XTemp\Tree\Component */
	protected $parent;
	
	/** @var array */
	protected $children;
	
	/** @var array */
	protected $resources;
	
	
	public function __construct() 
	{
		$this->parent = NULL;
		$this->children = array();
		$this->resources = array();
	}

	public function toString()
	{
		return get_class($this);
	}
	
	public function setTree($tree)
	{
		$this->tree = $tree;
		foreach ($this->getChildren() as $child)
			$child->setTree($tree);
	}
	
	public function getTree()
	{
		return $this->tree;
	}
	
	public function getResources()
	{
		return $this->resources;
	}
	
	public function addResource($resource)
	{
		$this->resources[] = $resource;
	}
	
	public function restructureTree()
	{
	}
	
	public function beforeRender()
	{
	}
	
	public function renderProlog()
	{
		return '';
	}
	
	/**
	 * Renders the component subtree rooted in this node to HTML.
	 * 
	 * @return A HTML string representing the whole subtree.
	 */
	abstract public function render();

	//============================= Basic tree operations ====================================
	
	/**
	 * @return XTemp\Tree\Component
	 */
	public function getParent()
	{
		return $this->parent;
	}
	
	/**
	 * @return array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * 
	 * @param XTemp\Tree\Component $child
	 */
	public function removeChild($child)
	{
		if (($key = array_search($child, $this->children)) !== FALSE)
			unset($this->children[$key]);
		$child->parent = NULL;
	}
	
	public function removeAllChildren()
	{
		foreach ($this->children as $child)
			$child->parent = NULL;
		$this->children = array();
	}
	
	/**
	 * 
	 * @param XTemp\Tree\Component $child
	 */
	public function addChild($child)
	{
		if ($child->parent !== NULL)
			$child->parent->removeChild($child);
		$child->parent = $this;
		$this->children[] = $child;
		if ($this->getTree())
			$child->setTree($this->getTree());
	}

	public function addAll($list)
	{
		foreach ($list as $child)
			$this->addChild($child);
	}
	
	protected function recursiveSetTree($root, $tree)
	{
		$root->tree = $tree;
		foreach ($root->getChildren() as $child)
			$this->recursiveSetTree($child, $tree);
	}
	
	//============================= Subtree processing methods ====================================
	
	protected function renderChildren()
	{
		$ret = '';
		foreach ($this->children as $child)
			$ret .= $child->render();
		return $ret;
	}
	
	protected function renderIf($test, $value, $code)
	{
		if ($test == "'$value'")
			return $code; //simplified variant: test would always evaluate to true
		else
		{
			return "{if $test === '$value'}" . $code . "{/if}"; 
		}
	}
	
	protected function renderNotIf($test, $value, $code)
	{
		if ($test == "'$value'")
			return ''; //simplified variant: test would always evaluate to true
		else
		{
			return "{if $test !== '$value'}" . $code . "{/if}"; 
		}
	}
	
	protected function renderSelect($test, $variants, $error = NULL)
	{
		$ret = '';
		
		$first = TRUE;
		foreach ($variants as $value => $code)
		{
			$cond = "$test === '$value'";
			if ($first)
				$ret .= "{if $cond}";
			else
				$ret .= "{elseif $cond}";
			$first = FALSE;

			$ret .= $code;
		}
		if ($error !== NULL)
			$ret .= "{else}{? throw new \XTemp\MissingAttributeException('" . addslashes($error) . "')}";
		$ret .= "{/if}";
		
		return $ret;
	}
	
}