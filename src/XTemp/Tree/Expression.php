<?php
/*
 * XTemp - XML Templating Engine for PHP
 * Expression.php created on 30. 7. 2014 by burgetr
 */

namespace XTemp\Tree;

/**
 * An expression used in the template.
 *
 * @author      burgetr
 */
class Expression
{
	protected $src;
	
	protected $constant;
	
	public function __construct($src)
	{
		$this->src = $src;
		$this->constant = FALSE;
	}

	public function toPHP()
	{
		return self::translate($this->src);
	}
	
	public function isLValue()
	{
		$v = trim($this->src);
		if (substr($v, 0, 2) == '^{' && substr($v, -1) == '}')
		{
			$ids = self::parseLValue(substr($v, 2, -1));
			return $ids !== NULL;
		}
		else
			return FALSE;
	}
	
	public function getLValueIdentifiers()
	{
		$v = trim($this->src);
		if (substr($v, 0, 2) == '^{' && substr($v, -1) == '}')
		{
			return self::parseLValue(substr($v, 2, -1));
		}
		else
			return NULL;
	}
	
	public function getLValueIdString()
	{
		if ($this->isLValue())
			return implode(':', $this->getLValueIdentifiers());
		else
			return NULL;
	}
	
	
	//=========================================================================
	
	/**
	 * Translates an attribute expression to PHP.
	 * The expression syntax:
	 * #{expression} is PHP-evaluated
	 * the rest is constant
	 *
	 * #{cid}_map => $cid . '_map'
	 * #{$node->value} => $node->value
	 *
	 * @param unknown $expr        	
	 */
	public static function translate($expr, $stats = NULL) 
	{
		$open = 0;
		$state = 0; // 0 = out {}, 1 = in #{}, 2 = in ^{}
		$buffer = '';
		$ret = '';
		
		if ($stats !== NULL)
		{
			$stats->nconst = 0;
			$stats->nexpr = 0;
		}
		
		for ($i = 0; $i < strlen($expr); $i ++)
		{
			$c = $expr [$i];
			$nc = ($i + 1 < strlen($expr)) ? $expr[$i + 1] : '';
			switch ($state)
			{
				case 0 :
					if (($c == '#' || $c == '^') && $nc == '{') 
					{
						if ($buffer)
							$ret .= ".'$buffer'";
						$buffer = '';
						$state = ($c == '#') ? 1 : 2;
						if ($stats != NULL)
							$stats->nexpr++;
						$open = 1;
						$i ++; // skip {
					} 
					else
					{
						if ($stats !== NULL && $buffer === '')
							$stats->nconst++;
						$buffer .= $c;
					}
					break;
				case 1:
					if ($c == '}') 
					{
						if ($open == 1) 
						{
							if ($buffer)
								$ret .= ".($buffer)";
							$buffer = '';
							$state = 0;
						} else
							$buffer .= $c;
						$open --;
					}
					else if ($c == '{') 
					{
						$open ++;
						$buffer .= $c;
					}
					else
						$buffer .= $c;
					break;
				case 2:
					if ($c == '}') 
					{
						if ($open == 1) 
						{
							if ($buffer)
								$ret .= "." . self::translateLValue($buffer);
							$buffer = '';
							$state = 0;
						} else
							$buffer .= $c;
						$open --;
					}
					else if ($c == '{') 
					{
						$open ++;
						$buffer .= $c;
					}
					else
						$buffer .= $c;
					break;
			}
		}
		if ($buffer || !$ret)
			$ret .= ".'$buffer'";
		
		return substr ( $ret, 1 ); // omit the leading '.'
	}
	
	public static function translateLValue($value)
	{
		$ids = self::parseLValue($value);
		if ($ids === NULL || count($ids) === 0)
		{
			throw new \XTemp\InvalidExpressionException("Invalid LValue expression '$value'");
		}
		else
		{
			$root = $ids[0];
			$expr = '$_xt_ctx->find("' . $root . '")';
			if (count($ids) > 1)
				$expr .= '->' . implode('->', array_slice($ids, 1));
			return $expr;
		}
	}
	
	public static function parseLValue($value)
	{
		echo "PARSE: $value<br>";
		$v = trim($value);
		if (strlen($v) > 2 && substr($v, 0, 1) == '$')
		{
			$v = substr($v, 1);
			$ids = self::splitIdString($v);
			$ret = array();
			foreach ($ids as $cand)
			{
				$id = trim($cand);
				if (self::isVarName($id))
					$ret[] = $id;
				else
					return NULL;
			}
			if (count($ret) == 0)
				return NULL;
			else
				return $ret;
		}
		else
			return NULL;
	}
	
	public static function splitIdString($expr)
	{
		$plevel = 0;
		$blevel = 0;
		$ret = array();
		$cur = '';
		
		for ($i = 0; $i < strlen($expr); $i ++)
		{
			$c = $expr[$i];
			$nc = ($i + 1 < strlen($expr)) ? $expr[$i + 1] : '';
			
			if ($c == '-' && $nc == '>')
			{
				if ($plevel === 0 && $blevel === 0)
				{
					$ret[] = $cur;
					$cur = '';
					$i++; //skip '>'
				}
				else
					$cur .= $c;
			}
			else
			{
				if ($c == '[')
				{
					$cur .= $c;
					$blevel++;
					if ($blevel == 1)
						$cur .= '#{';
				}
				else if ($c == ']')
				{
					if ($blevel == 1)
						$cur .= '}';
					$cur .= $c;
					$blevel = $blevel > 0 ? $blevel - 1 : $blevel;
				}
				else if ($c == '(')
				{
					$cur .= $c;
					$plevel++;
					if ($plevel == 1)
						$cur .= '#{';
				}
				else if ($c == ')')
				{
					if ($plevel == 1)
						$cur .= '}';
					$cur .= $c;
					$plevel = $plevel > 0 ? $plevel - 1 : $plevel;
				}
				else
					$cur .= $c;
			}
		}
		if ($cur)
			$ret[] = $cur;
		echo "<pre>"; print_r($ret); echo "</pre>";
		return $ret;
	}  
	
	protected static function isVarName($name)
	{
		return (preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $name) === 1);
	}
	
	/**
	 * Checks whether the value is a constant (it contains no expressions).
	 * @param unknown $value
	 * @return boolean
	 */
	public static function isConstant($value)
	{
		$stats = new \stdClass();
		self::translate($value, $stats);
		return ($stats->nconst <= 1 && $stats->nexpr == 0);
	}
	
}
