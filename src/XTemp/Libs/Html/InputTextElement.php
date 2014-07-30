<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputText.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
class InputTextElement extends InputField
{
	private $required;
	private $requiredMessage;
	
	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->required = $this->useAttrPlain("required", "false", array("true", "false"));
		$this->requiredMessage = $this->useAttrExpr("requiredMessage", 'Value required');
	}

	public function beforeRender()
	{
		parent::beforeRender();
	}
	
	public function render()
	{
		return '{input ' . $this->id . '}';
	}
	
	public function getFnCall()
	{
		$lbl = '$labels[' . $this->id . ']';
		$ret = 'addText(' . $this->id . ", isset($lbl)?$lbl:'')";
		if ($this->required == "true")
		{
			$ret .= '->setRequired(' . $this->requiredMessage->toPHP() . ')';
		}
		return $ret;
	}
}