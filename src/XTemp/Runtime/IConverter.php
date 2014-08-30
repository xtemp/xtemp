<?php
/*
 * XTemp - XML Templating Engine for PHP
 * IConverter.php created on 30. 8. 2014 by burgetr
 */

namespace XTemp\Runtime;

/**
 *
 * @author      burgetr
 */
interface IConverter
{
	
	public function getAsString($context, $params, $value);
	
	public function getAsObject($context, $params, $value);
	
}
