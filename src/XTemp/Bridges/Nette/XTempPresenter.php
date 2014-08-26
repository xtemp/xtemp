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
	
	
	public function startup()
	{
		parent::startup();
		$this->template->getLatte()->setLoader(new \XTemp\Loader($this));
		$this->restoreSessionProperties();
		$this->restoreSessionForms();
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
			if (isset($this->_xt_forms[$name]))
			{
				/*echo "Reconstruct<pre>";
				print_r($this->_xt_forms[$name]);
				echo "</pre>";*/
				$ret = XTempForm::constructForm($this, $this->_xt_forms[$name]);
				$ret->onSuccess[] = $this->_xt_processForm;
			}
			else
				echo "not found " . count($this->_xt_forms);
				
		}
		return $ret;
	}
	
	//===========================================================================
	
	public function _xt_processForm(XTempForm $form)
	{
		$mapping = $form->getMapping();
		foreach ($form->getValues(TRUE) as $name => $value)
		{
			if (isset($mapping[$name]) && $mapping[$name] != '')
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
			if (isset($mapping[$name]) && $mapping[$name] != '')
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
			$idx = '';
			
			//locate the array index if used
			$p1 = strpos($prop, '[');
			$p2 = strpos($prop, ']');
			if ($p1 !== FALSE && $p2 !== FALSE && $p2 > $p1)
			{
				$idx = substr($prop, $p1+1, $p2 - $p1 - 1);
				$prop = substr($prop, 0, $p1);
			}
			
			//reach the right property
			if ($i === 0 && $prop == 'this')
				$srcobj = $this;
			else if (isset($srcobj->$prop))
				$srcobj = $srcobj->$prop;
			else
				throw new InvalidExpressionException("Couldn't find the property $prop from $str in the presenter");
			
			//reach the index if used
			if ($idx !== '')
			{
				$srcobj = $srcobj[$idx];
			}
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
	
}