<?php
/*
 * XTemp - XML Templating Engine for PHP
 * FormDef.php created on 23. 8. 2014 by burgetr
*/

namespace XTemp\Bridges\Nette;

/**
 * A lightweight form definition to be stored and restored.
 * @author burgetr
 */
class FormDef
{
	/** Field labels (name=>label) */
	public $labels;
	/** Field types (name=>class) */
	public $types;
	/** Default values (name=>value) */
	public $values;
	/** Field mapping (name=>mapping) */
	public $mappings;
	
	public function __construct()
	{
		$this->labels = array();
		$this->types = array();
		$this->values = array();
		$this->mappings = array();
	}
}