<?php
/*
 * XTemp - XML Templating Engine for PHP
 * PublicResource.php created on 13. 9. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 * A representation of an external resource (javascript, css, etc.) required by a component
 * that is accessible from a third-party source (URL)
 *
 * @author      burgetr
 */
class PublicResource extends Resource
{
	protected $id;
	protected $version;
	
	public function __construct($id, $version, $url, $mime, $rel = NULL)
	{
		parent::__construct($url, $mime, $rel);
		$this->id = $id;
		$this->version = $version;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function isBetterThan($other)
	{
		return ($this->getVersion() > $other->getVersion());
	}
	
	public function getVersion()
	{
		return $this->version;
	}
	
	public function getEmbeddedPath()
	{
		return $this->path;
	}
	
	public function getLocalPath()
	{
		return $this->path;
	}
}