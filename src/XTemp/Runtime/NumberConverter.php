<?php
/*
 * XTemp - XML Templating Engine for PHP
 * NumberConverter.php created on 31. 8. 2014 by burgetr
 */

namespace XTemp\Runtime;

use XTemp\ConverterException;
/**
 *
 * @author      burgetr
 */
class NumberConverter implements IConverter
{
	
	public function getAsString($context, $params, $value)
	{
		$decimals = isset($params['decimals']) ? $params['decimals'] : 0;
		$decPoint = isset($params['decPoint']) ? $params['decPoint'] : '.';
		$thousandsSep = isset($params['thousandsSep']) ? $params['thousandsSep'] : ',';

		if (!$value) $value = 0; //everything that may be converted to 0
		return number_format($value, $decimals, $decPoint, $thousandsSep);
	}
	
	public function getAsObject($context, $params, $value)
	{
		$decimals = isset($params['decimals']) ? $params['decimals'] : 0;
		$decPoint = isset($params['decPoint']) ? $params['decPoint'] : '.';
		$thousandsSep = isset($params['thousandsSep']) ? $params['thousandsSep'] : ',';
		
		$val = $value;
		if ($thousandsSep)
			$val = str_replace($thousandsSep, '', $val);
		if ($decPoint != '.')
			$val = str_replace($decPoint, '.', $val);
		
		return floatval($val);
	}
	
}
