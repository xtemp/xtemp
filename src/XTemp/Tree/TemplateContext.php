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
	
	protected $mapping;
	
	
	public function __construct($presenter)
	{
		$this->presenter = $presenter;
		$this->mapping = array();
	}
	
	public function map($root)
	{
		//TODO
		return $this->presenter->$root;
	}
	
}
