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
		$this->value = $this->requireAttrExpr('value');
		$this->encoding = $this->useAttrExpr('encoding', 'string');
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
		$v = $this->value;
		
		return $this->renderSelect($this->encoding,
				array(
					'json' => '{!= json_encode(' . $v . ')}',
					'string' => '{= ' . $v . '}'
				),
				"Invalid encoding value: $this->encoding");
	}
}