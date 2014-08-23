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
	/** @SessionScoped */
	protected $_xt_forms;
	
	protected $_xt_formName;
	
	
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
		echo "creating $name";
		$ret = parent::createComponent($name);
		if ($ret === NULL)
		{
			if (isset($this->_xt_forms[$name]))
			{
				echo "Reconstruct<pre>";
				print_r($this->_xt_forms[$name]);
				echo "</pre>";
				$ret = XTempForm::constructForm($this, $this->_xt_forms[$name]);
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
	
	public function startFormRendering($formName)
	{
		$this->_xt_formName = $formName;
		$this->_xt_forms[$formName] = new FormDef();
	}
	
	protected function formRenderingPrefix()
	{
		$ret = "<?php\n";
		$ret .= 'function _xt_create_form($presenter){' . "\n";
		$ret .= '$labels = array();';
		$ret .= '$form = new \XTemp\Bridges\Nette\XTempForm;';
		return $ret;
	}
	
	protected function formRenderingSuffix()
	{
		$ret = '';
		$ret .= 'return $form;';
		$ret .= "}\n";
		return $ret;
	}
	
	public function addFormLabel($name, $text)
	{
		$this->_xt_forms[$this->_xt_formName]->labels[$name] = $text;
	}
	
	public function addFormField($name, $type, $mapping, $default)
	{
		$def = $this->_xt_forms[$this->_xt_formName];
		$def->types[$name] = $type;
		$def->mappings[$name] = $mapping;
		$def->values[$name] = $default;
	}
	
	public function finishFormRendering()
	{
	}
	
}