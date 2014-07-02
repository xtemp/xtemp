<?php
/*
* XTemp - XML Templating Engine for PHP
* ParamElement.php created on 2. 7. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

use XTemp\MissingAttributeException;
/**
*
* @author      burgetr
*/
class ParamElement extends \XTemp\Tree\Element
{
	private $value;
	private $encoding;

	public function __construct($domElement)
	{
		parent::__construct($domElement);
		$this->value = $this->requireAttr('value');
		$this->encoding = $this->useAttr('encoding', 'string');
		if ($this->encoding != "string"
			&& $this->encoding != "json")
			throw new MissingAttributeException("Invalid encoding value: " . $this->encoding);
	}
	
	public function render()
	{
		return "";
	}

	/**
	 * Returns the value encoded by the given encoding.
	 */
	public function getValue()
	{
		$v = $this->translateExpr($this->value);
		if ($this->encoding == 'json')
			return '{!= json_encode(' . $v . ')}';
		else
			return '{= ' . $v . '}';
	}
}