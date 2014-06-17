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
	
	
	public function __construct() 
	{
		$this->parent = NULL;
		$this->children = array();
	}

	public function toString()
	{
		return get_class($this);
	}
	
	public function setTree($tree)
	{
		$this->tree = $tree;
	}
	
	public function getTree()
	{
		return $this->tree;
	}
	
	public function restructureTree()
	{
	}
	
	public function beforeRender()
	{
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
		$this->recursiveSetTree($child, $this->getTree());
		$child->parent = $this;
		$this->children[] = $child;
	}

	public function addAll($list)
	{
		foreach ($list as $child)
			$this->addChild($child);
	}
	
	protected function recursiveSetTree($root, $tree)
	{
		$root->setTree($tree);
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
	
}