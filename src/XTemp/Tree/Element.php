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
	protected static $serialNum = 1;
	protected $domElement;
	protected $attributes;
	
	public function __construct($domElement)
	{
		parent::__construct();
		$this->domElement = $domElement;
		$this->loadAttributes();
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
	
	//================================= Rendering Utilities ===========================================
	
	public function getSimpleName()
	{
		$name = $this->domElement->nodeName;
		if ($pp = strpos($name, ':') !== FALSE) //strip namespace from the tag name
			$name = substr($name, $pp + 1);
		return $name;
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
		return $name . '="' . $this->attributes[$name] . '"';
	}
	
	//================================= Attribute Utilities ===========================================

	protected function loadAttributes()
	{
		$this->attributes = array();
		foreach ($this->domElement->attributes as $attr)
			$this->attributes[$attr->nodeName] = $attr->nodeValue;
	}
	
	protected function checkId()
	{
		if (!isset($this->attributes['id']))
			$this->attributes['id'] = $this->generateId();
		return $this->attributes['id'];
	}
	
	protected function generateId()
	{
		return '_xt_' . (Element::$serialNum++);
	}
	
	protected function requireAttr($name)
	{
		if ($this->domElement->hasAttribute($name))
			return $this->domElement->getAttribute($name);
		else
			throw new \XTemp\MissingAttributeException("Missing attribute '$name' of the <{$this->domElement->nodeName}> element");
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
		$v = $this->requireAttr($name);
		if ($this->isVarName($v))
			return $v;
		else
			throw new \XTemp\MissingAttributeException("Attribute '$name' requires a variable name");
	}
	
	protected function useAttr($name, $default)
	{
		if ($this->domElement->hasAttribute($name))
			return $this->domElement->getAttribute($name);
		else
			return $default;
	}
	
	protected function isVarName($name)
	{
		return (preg_match('/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $v) === 1);
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
		$filter = new \XTemp\Filter();
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