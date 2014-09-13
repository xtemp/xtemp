<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Resource.php created on 24. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 * A representation of an external resource (javascript, css, etc.) required by a component.
 *
 * @author      burgetr
 */
abstract class Resource
{
	protected $path;
	protected $mime;
	protected $rel;
	
	public function __construct($path, $mime, $rel = NULL)
	{
		$this->path = $path;
		$this->mime = $mime;
		$this->rel = $rel;
	}
	
	public function getPath()
	{
		return $this->path;
	}
	
	public function getMime()
	{
		return $this->mime;
	}
	
	public function getRel()
	{
		return $this->rel;
	}
	
	abstract public function getEmbeddedPath();
	
	abstract public function getLocalPath();
	
	abstract public function getId();
	
	abstract public function isBetterThan($other);
	
}