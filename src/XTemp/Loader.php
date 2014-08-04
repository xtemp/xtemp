<?php

namespace XTemp;

use Nette,
Latte;


/**
 * 
 *
 * @author Radek Burget
 */
class Loader extends Latte\Loaders\FileLoader
{
	/** @var Nette\Application\UI\Presenter */
	private $presenter;

	/** @var XTemp\Filter */
	private $filter;
	
	private $temp;
	
	
	public function __construct(Nette\Application\UI\Presenter $presenter)
	{
		$this->presenter = $presenter;
		
		$context = new Context();
		$context->presenter = $this->presenter;
		$this->filter = new Filter($context);
		
	    $params = $this->presenter->context->getParameters();
        $this->temp = $params['tempDir'] . "/cache/xtemp.deps/" . $this->presenter->name . "/";
        if (!file_exists($this->temp))
        {
            if (!mkdir($this->temp, 0777, true))
            	throw new \RuntimeException("Unable to create cache directory '" . $this->temp . "'.");
        }
	}

	public function getContent($file)
	{
		$src = parent::getContent($file);
		$ret = $this->filter->process($src, $file);
		$deps = $this->filter->getDependencies();
		$this->saveDeps($deps);
		return $ret;
	}
	
	public function isExpired($file, $time)
	{
		if (parent::isExpired($file, $time))
			return TRUE; //base template expired
		else
		{
			//check dependencies
			$deps = $this->loadDeps();
			foreach ($deps as $dfile)
			{
				if (parent::isExpired($dfile, $time))
					return TRUE;
			}
			return FALSE;
		}
	}
	
	private function getDepsFile()
	{
		return $this->temp . $this->presenter->getView() . ".deps";
	}
	
	private function saveDeps($deps)
	{
		$content = implode("\n", $deps);
		file_put_contents($this->getDepsFile(), $content);
	}
	
	private function loadDeps()
	{
		if (is_file($this->getDepsFile()))
		{
			$content = file_get_contents($this->getDepsFile());
			if ($content === FALSE)
				return array();
			else
				return explode("\n", $content);
		}
		else
			return array();
	}
}
