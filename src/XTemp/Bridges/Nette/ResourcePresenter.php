<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ResourcePresenter.php created on 23. 6. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

use XTemp\Filter;
use XTemp\Context;

/**
 * A presenter used to server component's additional resources (scripts, css, etc.)
 *
 * @author      burgetr
 */
class ResourcePresenter extends XhtmlPresenter
{
	private $paths;
	
	public function __construct()
	{
		parent::__construct();
		$this->paths = array();
	}
	
	public function addPath($library, $path)
	{
		if ($path)
		{
			if (substr($path, 0, 1) != '/' && substr($path, 0, 1) != '\\')
				$path = '/' . $path;
			$this->paths[$library] = $path;
		}
	}
	
	public function startup()
	{
		parent::startup();
		$filter = new Filter(new Context());
		$this->paths = $filter->getResourcePaths();
	}
	
	public function renderResource($path, $t)
	{
		$path = $this->translatePath($path);
		if ($path && (is_file($path) || is_link($path)))
		{
			$mime = $t;
			if (!$mime)
			{
				//guess the MIME type
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $path);
				finfo_close($finfo);
			}
			
			//send the response
			$response = new \Nette\Application\Responses\FileResponse($path, NULL, $mime);
			$this->context->getService('session')->close();
			$this->sendResponse($response);
			$this->terminate();
		}
		else
		{
			$resp = $this->getContext()->getService('httpResponse');
			$resp->setCode(\Nette\Http\Response::S404_NOT_FOUND);
		}
	}
	
	private function translatePath($path)
	{
		foreach ($this->paths as $src => $dest)
		{
			if (strpos($path, $src) === 0)
			{
				$len = strlen($src);
				$ret = $dest . '/' . substr($path, $len);
				return $ret;
			}
		}
		return NULL;
	}
	
}