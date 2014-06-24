<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ResourcePresenter.php created on 23. 6. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

use XTemp\Filter;

/**
 * A presenter used to server component's additional resources (scripts, css, etc.)
 *
 * @author      burgetr
 */
class ResourcePresenter extends XTempPresenter
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
		$filter = new Filter();
		$this->paths = $filter->getResourcePaths();
	}
	
	public function renderResource($path)
	{
		print_r($this->paths);
		if ($this->isAllowed($path))
		{
			if (is_file($path) || is_link($path))
			{
				//guess the MIME type
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $path);
				finfo_close($finfo);
				
				//send the response
				$response = new \Nette\Application\Responses\FileResponse($path, NULL, $mime);
				$this->context->session->close();
				$this->sendResponse($response);
				$this->terminate();
			}
			else
			{
				$resp = $this->getContext()->getService('httpResponse');
				$resp->setCode(\Nette\Http\Response::S404_NOT_FOUND);
			}
		}
		else
		{
			$resp = $this->getContext()->getService('httpResponse');
			$resp->setCode(\Nette\Http\Response::S403_FORBIDDEN);
		}
	}
	
	private function isAllowed($path)
	{
		foreach ($this->paths as $allow)
		{
			if (strpos($path, $allow) === 0)
				return TRUE;
		}
		return FALSE;
	}
	
}