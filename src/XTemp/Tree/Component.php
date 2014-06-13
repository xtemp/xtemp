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

	public function beforeRender()
	{
	}
	
	/**
	 * Adds this node to the tree. Standard behavior is to add the node under the parent node
	 * or to make it a tree root when the parent is <code>NULL</code>. However, some components
	 * may put themselves to different places of the tree. 
	 * 
	 * @param XTemp\Tree\ComponentTree $tree
	 * @param XTemp\Tree\Component $parent
	 */
	public function addToTree($tree, $parent)
	{
		$this->tree = $tree;
		if ($parent)
			$parent->addChild($this);
		else
			$tree->setRoot($this);
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