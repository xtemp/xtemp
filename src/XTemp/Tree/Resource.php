<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Resource.php created on 24. 6. 2014 by burgetr
 */

namespace XTemp;

/**
 * A representation of an external resource (javascript, css, etc.) required by a component.
 *
 * @author      burgetr
 */
class Resource
{
	private $library;
	private $path;
	private $mime;
	private $rel;
	
	public function __construct($library, $path, $mime, $rel = NULL)
	{
		$this->library = $library;
		$this->path = $path;
		$this->mime = $mime;
		$this->rel = $rel;
	}
	
	public function getLibrary()
	{
		return $this->library;
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
	
	public function getEmbeddedPath()
	{
		return $this->library . '/' . $this->path;
	}
	
	public function getLocalPath()
	{
		return str_replace('.', '/', $this->library) . '/' . $this->path;
	}
}