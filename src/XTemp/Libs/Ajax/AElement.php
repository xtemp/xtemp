<?php
/*
 * XTemp - XML Templating Engine for PHP
 * AElement.php created on 2. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Ajax;

/**
 *
 * @author      burgetr
 */
class AElement extends \XTemp\Tree\Element
{
	private $action;
	private $rerender;
	private $params;
	
	public function __construct(\DOMElement $domElement, \XTemp\Context $context)
	{
		parent::__construct($domElement, $context);
		Ajax::requireStdResources($this);
	}
	
	protected function loadParams()
	{
		//$this->href = $this->requireAttrExpr('href');
		$this->action = $this->useAttrExpr('action', NULL);
		$this->rerender = $this->useAttrExpr('reRender', NULL);
		$this->params = $this->useAttrExpr('params', NULL);
	}
	
	public function render()
	{
		$p = '';
		if ($this->action !== NULL)
			$p .= ",'a'=>" . $this->action->toPHP();
		if ($this->rerender !== NULL)
			$p .= ",'r'=>" . $this->rerender->toPHP();
		if ($this->params !== NULL)
			$p .= ",'p'=>" . $this->params->toPHP();
		
		$ret = '';
		$ret .= '<a href="#" onclick="XtAjax.link({link _xt_signal! ' . $p . ' }); return false;">';
		$ret .= $this->renderChildren();
		$ret .= '</a>';
		return $ret;
	}

}