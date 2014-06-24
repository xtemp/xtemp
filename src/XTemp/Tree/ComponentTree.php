<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ComponentTree.php created on 13. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 *
 * @author      burgetr
 */
class ComponentTree
{
	private $file;
	private $root;
	protected $dependencies = array();
	
	public function __construct($file)
	{
		$this->file = $file;
	}
	
	public function setRoot($root)
	{
		$this->root = $root;
	}
	
	public function getRoot()
	{
		return $this->root;
	}
	
	public function getFile()
	{
		return $this->file;
	}
	
	public function addDependency($path)
	{
		if (!in_array($path, $this->dependencies))
			$this->dependencies[] = $path;
	}
	
	public function getDependencies()
	{
		return $this->dependencies;
	}

	public function getAllResources()
	{
		 $resources = $this->recursiveGetResources($this->getRoot());
		 $ret = array();
		 //unify duplicate resources
		 foreach ($resources as $res)
		 {
		 	$ret[$res->getEmbeddedPath()] = $res;
		 }
		 return $ret;
	}
	
	protected function recursiveGetResources($root)
	{
		$ret = $root->getResources();
		foreach ($root->getChildren() as $child)
			$ret = array_merge($ret, $this->recursiveGetResources($child));
		return $ret;
	}
	
	public function render()
	{
		return $this->renderProlog($this->root) . $this->root->render();
	}
	
	//=========================================================================================
	
	public function renderProlog($root)
	{
		$ret = $root->renderProlog();
		foreach ($root->getChildren() as $child)
			$ret .= $this->renderProlog($child);
		return $ret;
	}
	
	//=========================================================================================
	
	public function dumpTree()
	{
		echo "<ul>\n";
		$this->recursiveDump($this->root);
		echo "</ul>\n";
	}
	
	private function recursiveDump($root)
	{
		echo '<li>' . $root->toString() . "\n";
		echo '<ul>';
		foreach ($root->getChildren() as $child)
			$this->recursiveDump($child);
		echo "</ul></li>\n";
	}
	
}