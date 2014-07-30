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
	
	public function __construct($src)
	{
		$this->src = $src;
	}

	public function toPHP()
	{
		return Expression::translate($this->src);
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
	public static function translate($expr) 
	{
		$open = 0;
		$state = 0; // 0 = out{}, 1 = in{}
		$buffer = '';
		$ret = '';
		
		for($i = 0; $i < strlen ( $expr ); $i ++)
		{
			$c = $expr [$i];
			$nc = ($i + 1 < strlen ( $expr )) ? $expr [$i + 1] : '';
			switch ($state)
			{
				case 0 :
					if ($c == '#' && $nc == '{') 
					{
						if ($buffer)
							$ret .= ".'$buffer'";
						$buffer = '';
						$state = 1;
						$open = 1;
						$i ++; // skip {
					} 
					else
						$buffer .= $c;
					break;
				case 1 :
					if ($c == '}') 
					{
						if ($open == 1) 
						{
							if ($buffer)
								$ret .= ".$buffer";
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
		if ($buffer)
			$ret .= ".'$buffer'";
		
		return substr ( $ret, 1 ); // omit the leading '.'
	}
	
	
}
