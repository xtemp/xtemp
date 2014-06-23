<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ResourcePresenter.php created on 23. 6. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

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
	
	public function addPath($path)
	{
		if ($path)
		{
			if (substr($path, 0, 1) != '/' && substr($path, 0, 1) != '\\')
				$path = '/' . $path;
			$this->paths[] = $path;
		}
	}
	
	public function renderResource($path)
	{
		if ($this->isAllowed($path))
		{
			if (is_file($path) || is_link($path))
			{
				//dibi::query("INSERT INTO [downloads] ([resource], [name], [size], [userid]) VALUES (%s, %s, %i, %i)", 'LICENSE', $filename, filesize($path), $user->getIdentity()->id);

				//guess the MIME type
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $path);
				finfo_close($finfo);
				
				$response = new \Nette\Application\Responses\FileResponse($path, NULL, $mime);
				
				$this->context->session->close();
				$this->sendResponse($response);
				$this->terminate();
			}
			else
			{
				$resp = $this->getContext()->getService('httpResponse');
				//$resp->setCode(\Nette\Http\Response::S404_NOT_FOUND);
				$resp->redirect('http://www.seznam.cz');
			}
		}
		else
		{
			$resp = $this->getContainer()->getService('httpResponse');
			$resp->setCode(\Nette\Http\Response::S403_FORBIDDEN);
		}
	}
	
	private function isAllowed($path)
	{
		/*foreach ($this->paths as $allow)
		{
			if (strpos($path, $allow) === 0)
				return TRUE;
		}
		return FALSE;*/
		return true;
	}
	
}