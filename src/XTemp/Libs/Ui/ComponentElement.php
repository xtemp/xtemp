<?php
/*
 * XTemp - XML Templating Engine for PHP
 * ComponentElement.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Libs\Ui;

/**
 *
 * @author burgetr
 */
class ComponentElement extends \XTemp\Tree\Element
{
	private $rendered; 
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->checkId();
		$this->rendered = $this->useAttrExpr('rendered', 'true');
	}

	public function restructureTree()
	{
		//ignore the rest of the tree, use this component as the rendering root
		$this->tree->setRoot($this);
	}
	
	public function render()
	{
		return $this->renderNotIf($this->rendered->toPHP(), 'false', $this->renderChildren());
	}
	
	
}