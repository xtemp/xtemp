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

	protected function loadParams()
	{
		$this->message = $this->useAttrExpr('message', NULL);
	}
	
	public function render()
	{
		return '';
	}

}