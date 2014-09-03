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
		if ($p !== NULL)
		{
			$p->addControlParam("converter", "\XTemp\Runtime\DateTimeConverter");
			$pat = $this->pattern;
			if ($pat === NULL)
			{
				switch ($this->type)
				{
					case 'time': $pat = 'H:i:s'; break;
					case 'both': $pat = 'j M Y H:i:s'; break;
					default: $pat = 'j M Y'; break;
				}
			}
			
			$parms = array('type'=>$this->type);
			$parms['pattern'] = $pat;
			if ($this->timeZone) $parms['timeZone'] = $this->timeZone;
			$p->addControlParam("converter_p", $parms);
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