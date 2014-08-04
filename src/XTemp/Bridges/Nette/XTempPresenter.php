<?php
/*
 * XTemp - XML Templating Engine for PHP
 * XTempPresenter.php created on 16. 6. 2014 by burgetr
 */

namespace XTemp\Bridges\Nette;

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
		if ($this->signal !== NULL && !$ret)  //TODO detekovat, kdy se zpracovava signal, jinak to nedelat
		{
			//the form doesn't exist: check if it was previously used in the template
			$mapping = $this->loadMapping($name);
			if ($mapping)
			{
				//the form mapping is known - it's a form defined in the template
				$ret = new XTempForm($this, $name); //create an empty form for make the event processing work
				$ret->onSuccess[] = $this->_xt_processForm;
				$ret->setMapping($mapping);
			}
		}
		return $ret;
	}
	
	//===========================================================================
	
	public function _xt_processForm($form)
	{
		$mapping = $form->getMapping();
		foreach ($form->getValues(TRUE) as $name => $value)
		{
			if (isset($mapping[$name]))
			{
				$prop = $mapping[$name];
				$this->$prop = $value; //TODO i strukturovane hodnoty
			}
		}
		//TODO volat obsluznou metodu
		//default redirect pokud obsluzna metoda neprovedla presmerovani
		$this->redirect('this');
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