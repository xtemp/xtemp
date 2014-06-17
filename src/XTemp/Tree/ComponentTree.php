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
	
	public function render()
	{
		return $this->root->render();
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