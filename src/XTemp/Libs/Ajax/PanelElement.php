<?php
/*
 * XTemp - XML Templating Engine for PHP
 * PanelElement.php created on 5. 10. 2014 by burgetr
 */

namespace XTemp\Libs\Ajax;

/**
 *
 * @author      burgetr
 */
class PanelElement extends \XTemp\Tree\Element
{
	private $id;
	
	public function __construct(\DOMElement $domElement, \XTemp\Context $context)
	{
		parent::__construct($domElement, $context);
		Ajax::requireStdResources($this);
	}
	
	protected function loadParams()
	{
		$this->id = $this->requireAttrPlain('id');
	}
	
	public function render()
	{
		$ret = '';
		$ret .= "\n{snippet " . $this->id . "}\n";
		$ret .= $this->renderChildren();
		$ret .= "\n{/snippet}\n";
		return $ret;  
	}

}