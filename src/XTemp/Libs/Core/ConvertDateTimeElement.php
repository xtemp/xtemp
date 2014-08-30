<?php
/*
* XTemp - XML Templating Engine for PHP
* ConvertDateTimeElement.php created on 30. 8. 2014 by burgetr
*/

namespace XTemp\Libs\Core;

use XTemp\Libs\Html\InputField;
/**
*
* @author      burgetr
*/
class ConvertDateTimeElement extends \XTemp\Tree\Element
{
	private $type;
	private $pattern;
	private $timeZone;

	protected function loadParams()
	{
		$this->type = $this->useAttrPlain('type', 'date', array('date', 'time', 'both'));
		$this->pattern = $this->useAttrPlain('pattern', NULL);
		$this->timeZone = $this->useAttrPlain('timeZone', NULL);
	}
	
	public function beforeRender()
	{
		$p = $this->getParent();
		if ($p instanceof InputField)
		{
			$p->addControlParam("converter", "\XTemp\Runtime\DateTimeConverter");
			$parms = array('type'=>$this->type);
			if ($this->pattern) $parms['pattern'] = $this->pattern;
			if ($this->timeZone) $parms['timeZone'] = $this->timeZone;
			$p->addControlParam("converter_p", json_encode($parms));
		}
	}
	
	public function render()
	{
		return '';
	}

	public function getType()
	{
		return $this->type;
	}
	
	public function getPattern()
	{
		return $this->pattern;
	}
}