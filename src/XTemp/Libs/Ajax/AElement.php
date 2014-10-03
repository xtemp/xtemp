<?php
/*
 * XTemp - XML Templating Engine for PHP
 * AElement.php created on 2. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Ajax;

use XTemp\Tree\LocalResource;
use XTemp\Tree\PublicResource;

/**
 *
 * @author      burgetr
 */
class AElement extends \XTemp\Tree\Element
{
	private $href;
	private $params;
	
	public function __construct(\DOMElement $domElement, \XTemp\Context $context)
	{
		parent::__construct($domElement, $context);
		$this->addResource(new PublicResource("jquery", "1.6.4", "http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js", "text/javascript"));
		$this->addResource(new LocalResource("xtemp.ajax", "ajax.js", "text/javascript"));
	}
	
	protected function loadParams()
	{
		$this->href = $this->requireAttrExpr('href');
		$this->params = $this->useAttrExpr('params', NULL);
	}
	
	public function render()
	{
		return $this->renderStartElement() . $this->renderChildren() . $this->renderEndElement();
	}

	protected function renderAttribute($name)
	{
		if ($name == 'href')
		{
			$p = '';
			if ($this->params !== NULL)
				$p = ", (expand) array" . $this->params->toPHP();
			return 'href="#" onclick="XtAjax.link({link ' . $this->href->toPHP() . $p . '}); return false;"';
		}
		else if ($name != 'params' && $name != 'onclick')
			return parent::renderAttribute($name); 
	}
	
}