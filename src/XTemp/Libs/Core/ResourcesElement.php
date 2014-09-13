<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ResourcesElement.php created on 24. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Core;

/**
 *
 * @author burgetr
 */
class ResourcesElement extends \XTemp\Tree\Element
{
	private $local;
	private $resourceLink;
	
	protected function loadParams()
	{
		$this->local = $this->useAttrPlain('local', 'false', array('true', 'false'));
		$defLink = ($this->local != "false") ? '/resources/' : ':Resource:resource'; 
		$this->resourceLink = $this->useAttrPlain('resourceLink', $defLink);
	}

	public function render()
	{
		$ret = "";
		$resources = $this->getTree()->getAllResources();
		foreach ($resources as $res)
			$ret .= $this->renderResource($res) . "\n";
		return $ret;
	}
	
	protected function renderResource($res)
	{
		$path = $res->getRenderedPath($this->resourceLink, $this->local != 'false');
		/*if ($this->local != 'false')
		{
			$path = '{$basePath}' . $this->resourceLink . $res->getLocalPath();
		}
		else
		{
			$path = "{link " . $this->resourceLink . ", path => '" . $res->getEmbeddedPath() . "'}";
		}*/
		
		$ret = "";
		switch ($res->getMime())
		{
			case "text/css":
				$ret = "<link type=\"text/css\" rel=\"stylesheet\" href=\"$path\">";
				break;
			case "text/javascript":
				$ret = "<script type=\"text/javascript\" src=\"$path\"></script>";
				break;
			default:
				$ret = "<link type=\"" . $res->getMime() . "\" rel=\"" . $res->getRel() . "\" href=\"$path\">";
				break;
		}
		return $ret;
	}
	
}