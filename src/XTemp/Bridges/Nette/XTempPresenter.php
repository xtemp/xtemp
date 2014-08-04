<?php
/*
 * XTemp - XML Templating Engine for PHP
 * XTempPresenter.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

use XTemp\InvalidExpressionException;
/**
 * A base presenter that integrates XTemp with Nette framework.
 *
 * @author burgetr
 */
class XTempPresenter extends \Nette\Application\UI\Presenter
{
	
	public function startup()
	{
		parent::startup();
		$this->template->getLatte()->setLoader(new \XTemp\Loader($this));
		$this->restoreSessionProperties();
	}
	
	public function shutdown($response)
	{
		parent::shutdown($response);
		$this->saveSessionProperties();
	}
	
	public function formatTemplateFiles()
	{
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$dir = dirname($this->getReflection()->getFileName());
		$dir = is_dir("$dir/templates") ? $dir : dirname($dir);
		return array(
				"$dir/templates/$presenter/$this->view.xhtml",
				"$dir/templates/$presenter.$this->view.xhtml",
		);
	}

	protected function createComponent($name)
	{
		$ret = parent::createComponent($name);
		if ($ret === NULL)
		{
			$ext = $this->getFormTempFile($name);
			if (is_file($ext))
			{
				include($ext);
				$ret = _xt_create_form($this);
				$ret->onSuccess[] = $this->_xt_processForm;
				
				$mapping = $this->loadMapping($name);
				if ($mapping)
				{
					$ret->setMapping($mapping);
				}
			}
			
		}
		return $ret;
	}
	
	//===========================================================================
	
	public function _xt_processForm(XTempForm $form)
	{
		$mapping = $form->getMapping();
		foreach ($form->getValues(TRUE) as $name => $value)
		{
			if (isset($mapping[$name]))
			{
				$dest = $this->decodeMapping($mapping[$name]);
				$obj = $dest[0];
				$prop = $dest[1];
				$obj->$prop = $value;
			}
		}
		
		//find and call the action method 
		$btn = $form->isSubmitted();
		if ($btn && $btn instanceof \Nette\ComponentModel\Component)
		{
			$name = $btn->getName();
			if (isset($mapping[$name]))
			{
				$dest = $this->decodeMapping($mapping[$name]);
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
		$srcobj = $this;
		for ($i = 0; $i < count($p) - 1; $i++)
		{
			$prop = $p[$i];
			if ($i === 0 && $prop == 'this')
				$srcobj = $this;
			else if (isset($srcobj->$prop))
				$srcobj = $srcobj->$prop;
			else
				throw InvalidExpressionException("Couldn't find the property $prop in $str");
		}
		return array($srcobj, $p[$i]);
	}
	
	//===========================================================================
	
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
			//echo "store: $prop = " . $this->$prop . "<br>";
			$props[$prop] = $this->$prop;
		}
		$session = $this->getSession('XTemp/SessionScope');
		$session->properties = $props;
	}
	
	protected function restoreSessionProperties()
	{
		$session = $this->getSession('XTemp/SessionScope');
		$props = $session->properties;
		foreach ($this->getSessionProperties() as $prop)
		{
			if (isset($props[$prop]))
				$this->$prop = $props[$prop];
		}
	}
	
	public function storeMapping($formId, $mapping)
	{
		$session = $this->getSession('XTemp/FormMapping');
		$session->$formId = $mapping;
	}
	
	public function loadMapping($formId)
	{
		$session = $this->getSession('XTemp/FormMapping');
		return $session->$formId;
	}

	//===========================================================================
	
	public function getFormTempFile($formName)
	{
		$params = $this->context->getParameters();
		$temp = $params['tempDir'] . "/cache/xtemp.forms/" . $this->name . "/";
		if (!file_exists($temp))
		{
			if (!mkdir($temp, 0777, true))
				throw new \RuntimeException("Unable to create cache directory '" . $temp . "'.");
		}
		$file = $temp . $formName . ".php";
		return $file;
	}
}