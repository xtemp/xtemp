<?php
/*
 * XTemp - XML Templating Engine for PHP
 * InputField.php created on 6. 7. 2014 by burgetr
 */

namespace XTemp\Libs\Html;

/**
 *
 * @author      burgetr
 */
abstract class InputField extends FormField
{
	protected $id;
	protected $value;
	
	protected $required;
	protected $requiredMessage;
	
	protected function loadParams()
	{
		$this->id = $this->checkId();
		$this->value = $this->requireAttrExpr('value');
		$this->required = $this->useAttrPlain("required", "false", array("true", "false"));
		$this->requiredMessage = $this->useAttrExpr("requiredMessage", 'Value required');
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setLabel(OutputLabelElement $label)
	{
	}

	public function render()
	{
		if ($this->form->inPhpMode())
			return $this->renderPhpControl();
		else
			return '{input ' . $this->id . '}';
	}
	
	protected function renderPhpControl()
	{
		$req = '';
		if ($this->required === 'true')
		{
			$req = "'required'=>TRUE,'requiredMessage'=>" . $this->requiredMessage->toPHP(); 
		}
		
		$ret = "<?php ";
		$ret .= '$presenter->addFormField(';
		$ret .= $this->id . ',';
		$ret .= "'" . get_called_class() . "',";
		$ret .= "'" . $this->getMappingValue() . "',";
		$ret .= $this->getValue()->toPHP() . ',';
		$ret .= 'array(' . $req . '));';
		$ret .= "?>\n";
		return $ret;
	}
	
	/**
	 * Returns the LValue mapped to this input field
	 * @return string the LValue map string or NULL when no mapping is available
	 */
	public function getMappingValue()
	{
		if ($this->value !== NULL && $this->value->isLValue())
			return implode(':', $this->value->getLValueIdentifiers());
		else
			return NULL;
	}
	
	/**
	 * Adds an input field of the given type to a form.
	 * @param XTempForm $form The form to add to
	 * @param string $name Field name
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param array $params Additional parametres (array)
	 */
	abstract public static function addToForm($form, $name, $label, $value, $params);
	
}