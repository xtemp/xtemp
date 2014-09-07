<?php
/*
* XTemp - XML Templating Engine for PHP
* Validator.php created on 7. 9. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

/**
*
* @author      burgetr
*/
class Validator extends \XTemp\Tree\Element
{
	protected $message;
	protected $allowEmpty;

	protected function loadParams()
	{
		$this->message = $this->useAttrExpr('message', NULL);
		$this->allowEmpty = $this->useAttrExpr('allowEmpty', NULL);
	}
	
	public function render()
	{
		return '';
	}

}