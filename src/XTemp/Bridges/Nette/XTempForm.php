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
		$this->createFields();
	}
	
	public function getMapping()
	{
		return $this->mapping;
	}
	
	/**
	 * Creates fake fileds for the mapping values in order to pass the values
	 * correctly from HTTP to the form.
	 */
	protected function createFields()
	{
		foreach ($this->mapping as $name => $value)
		{
			$this->addText($name);
		}
	}
}