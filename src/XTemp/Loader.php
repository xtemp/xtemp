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

	/** @var TagLib\TagLibFilter */
	private $filter;
	
	
	
	public function __construct(Nette\Application\UI\Presenter $presenter)
	{
		$this->presenter = $presenter;
		$this->filter = new Filter();
	}

	public function getContent($file)
	{
		$src = parent::getContent($file);
		return $this->filter->process($src);
	}
	
}
