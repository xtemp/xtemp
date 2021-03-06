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
	private $name;
	private $value;
	private $encoding;

	protected function loadParams()
	{
		$this->name = $this->useAttrPlain('name', NULL);
		$this->value = $this->requireAttrExpr('value');
		$this->encoding = $this->useAttrExpr('encoding', 'string');
	}
	
	public function render()
	{
		return "";
	}

	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Returns the value encoded by the given encoding.
	 */
	public function getValue()
	{
		$v = $this->value->toPHP();
		
		return $this->renderSelect($this->encoding->toPHP(),
				array(
					'json' => '{!= json_encode(' . $v . ')}',
					'string' => '{= ' . $v . '}'
				),
				"Invalid encoding value: " . $this->encoding->toPHP());
	}
}