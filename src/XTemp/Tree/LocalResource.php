<?php
/*
 * XTemp - XML Templating Engine for PHP
 * LocalResource.php created on 13. 9. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 * A representation of an external resource (javascript, css, etc.) required by a component
 * that is distributed together with the application.
 *
 * @author      burgetr
 */
class LocalResource extends Resource
{
	protected $library;
	protected $path;
	protected $mime;
	protected $rel;
	
	public function __construct($library, $path, $mime, $rel = NULL)
	{
		parent::__construct($path, $mime, $rel);
		$this->library = $library;
	}
	
	public function getId()
	{
		//local resources are identified by their embedded paths
		return $this->getRenderedPath("default", FALSE);
	}
	
	public function isBetterThan($other)
	{
		//local resources are not compared
		return FALSE;
	}
	
	public function getLibrary()
	{
		return $this->library;
	}
	
	public function getRenderedPath($resourceLink, $local)
	{
		if ($local)
			return '{$basePath}' . $resourceLink . str_replace('.', '/', $this->library) . '/' . $this->path;
		else
			return "{link " . $resourceLink . ", path => '" . $this->library . '/' . $this->path . "'}"; 
	}
	
}