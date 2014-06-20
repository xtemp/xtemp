<?php
/*
 * XTemp - XML Templating Engine for PHP
 * LocalScope.php created on 20. 6. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 * A subtree that has local parametres. The parametres are accessible only in the subtree.
 *
 * @author burgetr
 */
class LocalScope extends Component
{
	private static $serialNum = 1;
	private $params;
	private $fname;
	
	public function __construct($params)
	{
		parent::__construct();
		$this->params = $params;
		$this->fname = '_xtemp_scope_' . (LocalScope::$serialNum++);
	}

	public function renderProlog()
	{
		$paramstr = '';
		foreach ($this->params as $name => $value)
		{
			if ($paramstr) $paramstr .= ',';
			$paramstr .= '$' . $name;
		}
		
		$ret = "<?php\nfunction " . $this->fname . "(" . $paramstr . ") {\n?>\n";
		foreach ($this->getChildren() as $child)
			$ret .= $child->render();
		$ret .= "<?php\n}\n?>\n";
		return $ret;
	}
	
	public function render()
	{
		$paramstr = '';
		foreach ($this->params as $name => $value)
		{
			if ($paramstr) $paramstr .= ',';
			$paramstr .= '"' . $value . '"';
		}
		
		return '<?php ' . $this->fname . "($paramstr); ?>";
	}
	
}