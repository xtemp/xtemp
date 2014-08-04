<?php
/*
 * XTemp - XML Templating Engine for PHP
* XTempForm.php created on 31. 7. 2014 by burgetr
*/

namespace XTemp\Bridges\Nette;

/**
 * A form that remembers the field mapping defined in the template
 *
 * @author burgetr
 */
class XTempForm extends \Nette\Application\UI\Form
{
	protected $mapping;
	
	public function setMapping($mapping)
	{
		$this->mapping = $mapping;
	}
	
	public function getMapping()
	{
		return $this->mapping;
	}
	
}