<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Element.php created on 6. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 *
 * @author      burgetr
 */
abstract class Element extends Component
{
	public static $serialNum = 1;
	protected $domElement;
	protected $attributes;
	protected $context;
	
	public function __construct(\DOMElement $domElement, \XTemp\Context $context)
	{
		parent::__construct();
		$this->domElement = $domElement;
		$this->context = $context;
		$this->loadAttributes();
		$this->loadParams();
	}
	
	public function toString()
	{
		return get_class($this) . '(' . $this->domElement->nodeName . ')';
	}
	
	public function getAttribute($name)
	{
		if (isset($this->attributes[$name]))
			return $this->attributes[$name];
		else
			return "";
	}
	
	public function getId()
	{
		if (isset($this->attributes['id']))
			return $this->attributes['id'];
		else
			return NULL;
	}
	
	public function getElementName()
	{
		$name = $this->domElement->nodeName;
		if ($pp = strpos($name, ':') !== FALSE) //strip namespace from the tag name
			$name = substr($name, $pp + 1);
		return $name;
	}
	
	/**
	 * Adds a control parameter value to the element. This is used by convertor
	 * elements to indicate a value conversion.
	 * @param unknown $name
	 * @param unknown $value
	 */
	public function addControlParam($name, $value)
	{
		//to be implemented by special elements when they accept some
		//control params.
	}
	
	//================================= Rendering Utilities ===========================================
	
	/**
	 * Obtains the name used for rendering the start element.
	 * Redefine this for changing the rendered element name.
	 */
	public function getSimpleName()
	{
		return $this->getElementName();
	}
	
	protected function renderStartElement()
	{
		$attrs = trim($this->renderAttributes());
		if ($attrs)
			$attrs = ' ' . $attrs;
		return '<' . $this->getSimpleName() . $attrs . '>';
	}
	
	protected function renderEndElement()
	{
		return '</' . $this->getSimpleName() . '>';
	}

	protected function renderAttributes()
	{
		$ret = '';
		foreach ($this->attributes as $name => $value)
			$ret .= ' ' . $this->renderAttribute($name);
		return $ret;
	}
	
	protected function renderAttribute($name)
	{
		return $name . '="{= ' . $this->attributes[$name] . '}"';
	}
	
	//================================= Attribute Utilities ===========================================

	/**
	 * Loads the element parametres from the DOM attributes. The elements should
	 * redefine this method in order to load their specific parametres. This is
	 * called automatically when the Element is created.
	 */
	protected function loadParams()
	{
	}
	
	protected function loadAttributes()
	{
		$this->attributes = array();
		foreach ($this->domElement->attributes as $attr)
			$this->attributes[$attr->nodeName] = Expression::translate($attr->nodeValue);
	}
	
	protected function checkId()
	{
		if (!isset($this->attributes['id']))
			$this->attributes['id'] = Expression::translate($this->generateId());
		return $this->attributes['id'];
	}
	
	protected function checkIdConstant()
	{
		if ($this->domElement->hasAttribute('id'))
		{
			$val = $this->domElement->getAttribute('id');
			if (Expression::isConstant($val))
			{
				$this->attributes['id'] = Expression::translate($val);
				return $val;
			}
			else
				throw \XTemp\InvalidExpressionException("No expressions allowed in the ID attribute of this element");
		}
		else
		{
			$val = $this->generateId();
			$this->attributes['id'] = Expression::translate($val);
			return $val;
		}
	}
	
	protected function generateId()
	{
		return '_xt_#{\XTemp\Tree\Element::$serialNum++}';
	}
	
	protected function requireAttrPlain($name, $allowed = NULL)
	{
		if ($this->domElement->hasAttribute($name))
		{
			$v = $this->domElement->getAttribute($name);
			if ($allowed === NULL || in_array(strtolower($v), $allowed))
				return $v;
			else
				throw new \XTemp\MissingAttributeException("Invalid value '$v' of the attribute '$name'");
		}
		else
			throw new \XTemp\MissingAttributeException("Missing attribute '$name' of the <{$this->domElement->nodeName}> element");
	}
	
	protected function useAttrPlain($name, $default, $allowed = NULL)
	{
		if ($this->domElement->hasAttribute($name))
		{
			$v = $this->domElement->getAttribute($name);
			if ($allowed === NULL || in_array(strtolower($v), $allowed))
				return $v;
			else
				throw new \XTemp\MissingAttributeException("Invalid value '$v' of the attribute '$name'");
		}
		else
			return $default;
	}
	
	protected function requireAttrExpr($name)
	{
		if ($this->domElement->hasAttribute($name))
			return new Expression($this->domElement->getAttribute($name));
		else
			throw new \XTemp\MissingAttributeException("Missing attribute '$name' of the <{$this->domElement->nodeName}> element");
	}
	
	protected function useAttrExpr($name, $default)
	{
		if ($this->domElement->hasAttribute($name))
			return new Expression($this->domElement->getAttribute($name));
		else if ($default !== NULL)
			return new Expression($default);
		else
			return null;
	}
	
	protected function requireAttrNum($name)
	{
		$v = $this->requireAttr($name);
		if (is_numeric($v))
			return $v;
		else
			throw new \XTemp\MissingAttributeException("Attribute '$name' requires a variable name");
	}
	
	protected function requireAttrVar($name)
	{
		$v = $this->requireAttrPlain($name);
		if ($this->isVarName($v))
			return $v;
		else
			throw new \XTemp\MissingAttributeException("Attribute '$name' requires a variable name");
	}

	protected function useAttrVar($name, $default)
	{
		$v = $this->useAttrPlain($name, $default);
		if ($v === $default || $this->isVarName($v))
			return $v;
		else
			throw new \XTemp\MissingAttributeException("Attribute '$name' requires a variable name");
	}

	protected function isVarName($name)
	{
		return (preg_match('/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $name) === 1);
	}
	
	//================================= Template nesting ==============================================
	
	protected function loadExternalTemplate($file)
	{
		//load the referenced template
		if (!is_file($file)) {
			throw new \RuntimeException("Missing template file '$file' referenced in '" . $this->getTree()->getFile() . "'");
		}
		$this->getTree()->addDependency($file);
		$src = file_get_contents($file);
		//create a new tree from the template
		$filter = new \XTemp\Filter($this->context);
		$tempTree = $filter->buildTree($src, $file);
		$filter->restructureTree($tempTree->getRoot());
		return $tempTree;
	}
	
	protected function addResourceTemplate($file, $params)
	{
		$cont = new LocalScope($params);
		$tree = $this->loadExternalTemplate($file);
		if ($tree && $tree->getRoot())
			$cont->addChild($tree->getRoot());
		$this->addChild($cont);
	}
	
}