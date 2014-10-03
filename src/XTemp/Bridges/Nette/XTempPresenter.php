<?php
/*
 * XTemp - XML Templating Engine for PHP
 * XTempPresenter.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

use XTemp\InvalidExpressionException;
use XTemp\Tree\Expression;
use \Tracy\Debugger;
use XTemp\ConverterException;
use XTemp\Runtime\IConverter;
/**
 * A base presenter that integrates XTemp with Nette framework.
 *
 * @author burgetr
 */
class XTempPresenter extends XhtmlPresenter
{
	/**
	 * Forms created from templates and stored in session.
	 * @var array
	 */
	protected $_xt_forms;
	
	/**
	 * Name of the form currently being created.
	 * @var unknown
	 */
	protected $_xt_formName;
	
	/**
	 * Saved element id generation status 
	 * @var unknown
	 */
	protected $_xt_id_status;
	
	
	protected function startup()
	{
		parent::startup();
		$this->template->getLatte()->setLoader(new \XTemp\Loader($this));
		$this->createServices();
		$this->restoreSessionProperties();
		$this->restoreSessionForms();
		$this->restorePresenterProperties();
	}
	
	public function shutdown($response)
	{
		parent::shutdown($response);
		$this->saveSessionProperties();
		$this->savePresenterProperties();
	}
	
	protected function createComponent($name)
	{
		$ret = parent::createComponent($name);
		if ($ret === NULL)
		{
			if (isset($this->_xt_forms[$name]))
			{
				/*echo "Reconstruct<pre>";
				print_r($this->_xt_forms[$name]);
				echo "</pre>";*/
				$ret = XTempForm::constructForm($this, $this->_xt_forms[$name]);
				$ret->onValidate[] = $this->_xt_validateForm;
				$ret->onSuccess[] = $this->_xt_processForm;
			}
			else
				echo "not found " . count($this->_xt_forms);
				
		}
		return $ret;
	}
	
	//===========================================================================

	public function _xt_validateForm(XTempForm $form)
	{
		$mapping = $form->getMapping();
		foreach ($form->getValues(TRUE) as $name => $value)
		{
			if (isset($mapping[$name]) && $mapping[$name] != '')
			{
				//validate the converters (if the value may be converted)
				try {
					$val = $this->applyConverter($form->getParams($name), $value);
				} catch (\Exception $e) {
					$msg = $e->getMessage();
					if (!$msg)
						$msg = "Converter error";
					$form[$name]->addError($msg);
				}
			}
		}
	}
	
	public function _xt_processForm(XTempForm $form)
	{
		$mapping = $form->getMapping();
		foreach ($form->getValues(TRUE) as $name => $value)
		{
			if (isset($mapping[$name]) && $mapping[$name] != '')
			{
				list($obj, $prop, $idx) = $this->decodeMapping($mapping[$name]);
				//echo "prop=$prop and idx=$idx and value=$value<br>";
				
				try {
					$val = $this->applyConverter($form->getParams($name), $value);
					if ($idx === NULL)
						$obj->$prop = $val;
					else
						$obj->{$prop}[$idx] = $val;
				} catch (\Exception $e) {
					$form->addError($e->getMessage());
				}
			}
		}
		
		//find and call the action method 
		$btn = $form->isSubmitted();
		if ($btn && $btn instanceof \Nette\ComponentModel\Component)
		{
			$name = $btn->getName();
			if (isset($mapping[$name]) && $mapping[$name] != '')
			{
				$destm = $this->decodeMapping($mapping[$name]);
				$dest = array($destm[0], $destm[1]); //omit the eventual index
				if (method_exists($dest[0], $dest[1]))
				{
					call_user_func($dest);
				}
				else
					throw new \XTemp\InvalidExpressionException("Couldn't find callback method $dest[1]");
			}
		}
		
		//default redirect when the action method did not redirect
		$this->redirect('this');
	}
	
	protected function decodeMapping($str)
	{
		$p = explode(':', $str);
		return Expression::findProperty($this, $p);
	}
	
	protected function applyConverter($params, $value)
	{
		if (isset($params['converter']))
		{
			$ccls = $params['converter'];
			$conv = new $ccls;
			$cparams = array();
			if (isset($params['converter_p']))
				$cparams = json_decode($params['converter_p'], TRUE);
			if ($conv instanceof IConverter)
				return $conv->getAsObject($this, $cparams, $value);
			else
				throw new ConverterException("$ccls must implement \XTemp\Runtime\IConverter");
		}
		else
			return $value;
	}
	
	//===========================================================================
	
	protected function createServices()
	{
		$reflection = $this->getReflection();
		$properties = $reflection->getProperties();
		foreach ($properties as $property)
		{
			if (!is_null($service = $property->getAnnotation('Service')))
			{
				$property->setAccessible(true);
				$property->setValue($this, $this->getContext()->getService($service));
			}
		}
	}
	
	public function getSessionProperties()
	{
		$rc = new \Nette\Reflection\ClassType(get_called_class());
		$params = array();
		foreach ($rc->getProperties() as $rp) {
			if (!$rp->isStatic() && $rp->hasAnnotation('SessionScoped')) {
				$params[] = $rp->getName();
			}
		}
		return $params;
	}
	
	protected function saveSessionProperties()
	{
		$props = array();
		foreach ($this->getSessionProperties() as $prop)
		{
			//Debugger::fireLog("store: $prop = " . get_class($this->$prop) . "<br>");
			$props[$prop] = $this->$prop;
		}
		$session = $this->getSession('XTemp/SessionScope');
		$session->properties = $props;
	}
	
	protected function restoreSessionProperties()
	{
		$session = $this->getSession('XTemp/SessionScope');
		$props = $session->properties;
		//Debugger::fireLog("RESTORE"); Debugger::fireLog($props);
		foreach ($this->getSessionProperties() as $prop)
		{
			if (isset($props[$prop]))
				$this->$prop = $props[$prop];
		}
	}
	
	public function getPresenterProperties()
	{
		$rc = new \Nette\Reflection\ClassType(get_called_class());
		$params = array();
		foreach ($rc->getProperties() as $rp) {
			if (!$rp->isStatic() && $rp->hasAnnotation('PresenterScoped')) {
				$params[] = $rp->getName();
			}
		}
		return $params;
	}
	
	protected function savePresenterProperties()
	{
		$props = array();
		foreach ($this->getPresenterProperties() as $prop)
		{
			//Debugger::fireLog("store: $prop = " . get_class($this->$prop) . "<br>");
			$props[$prop] = $this->$prop;
		}
		$session = $this->getSession('XTemp/PresenterScope/' . $this->getName());
		$session->properties = $props;
	}
	
	protected function restorePresenterProperties()
	{
		$session = $this->getSession('XTemp/PresenterScope/' . $this->getName());
		$props = $session->properties;
		//Debugger::fireLog("RESTORE"); Debugger::fireLog($props);
		foreach ($this->getPresenterProperties() as $prop)
		{
			if (isset($props[$prop]))
				$this->$prop = $props[$prop];
		}
	}
	
	public function saveSessionForms()
	{
		$session = $this->getSession('XTemp/Forms/' . $this->getName());
		$session->forms = $this->_xt_forms;
	}
	
	public function restoreSessionForms()
	{
		$session = $this->getSession('XTemp/Forms/' . $this->getName());
		$this->_xt_forms = $session->forms;
	}

	//===========================================================================
	
	public function startFormRendering($formName)
	{
		$this->_xt_formName = $formName;
		$this->_xt_forms[$formName] = new FormDef();
		//save the ID generation status: we want to reset it back after rendering
		$this->_xt_id_status = \XTemp\Tree\Element::$serialNum;
		//no ouput during the form definition rendering
		ob_start();
	}
	
	public function addFormLabel($name, $text)
	{
		$this->_xt_forms[$this->_xt_formName]->labels[$name] = $text;
	}
	
	public function addFormField($name, $type, $mapping, $default, $params)
	{
		$def = $this->_xt_forms[$this->_xt_formName];
		$def->types[$name] = $type;
		$def->mappings[$name] = $mapping;
		$def->values[$name] = $default;
		$def->params[$name] = $params;
	}
	
	public function finishFormRendering()
	{
		$this->saveSessionForms();
		//restre the ID generation status
		\XTemp\Tree\Element::$serialNum = $this->_xt_id_status;
		//re-enable PHP output
		ob_clean();
	}
	
	public function _xt_frm_param($formName, $fieldName, $paramName)
	{
		if (isset($this->_xt_forms[$formName]))
		{
			$def = $this->_xt_forms[$formName];
			if (isset($def->params[$fieldName]))
			{
				$params = $def->params[$fieldName];
				if (isset($params[$paramName]))
					return $params[$paramName];
				else
					return NULL;
			}
			else
				return NULL;
		}
		else
			return NULL;
		return NULL;
	}
	
}