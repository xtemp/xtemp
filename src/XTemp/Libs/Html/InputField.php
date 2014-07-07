<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputField.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
abstract class InputField extends \XTemp\Tree\Element
{
	protected $id;
	protected $value;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->id = $this->checkId();
		$this->value = $this->requireAttrExpr('value');
	}
	
	abstract public function getFnCall();
	
}