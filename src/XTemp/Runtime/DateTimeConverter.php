<?php
/*
 * XTemp - XML Templating Engine for PHP
 * DateTimeConverter.php created on 30. 8. 2014 by burgetr
 */

namespace XTemp\Runtime;

use XTemp\ConverterException;
/**
 *
 * @author      burgetr
 */
class DateTimeConverter implements IConverter
{
	
	public function getAsString($context, $params, $value)
	{
		$format = isset($params['pattern']) ? $params['pattern'] : 'r';
		
		if ($value instanceof \DateTime)
		{
			return $value->format($format);
		}
		else
			throw new ConverterException("Couldn't convert " . get_class($value) . " to date");
	}
	
	public function getAsObject($context, $params, $value)
	{
		$format = isset($params['pattern']) ? $params['pattern'] : NULL;
		$timeZone = isset($params['timeZone']) ? new \DateTimeZone($params['timeZone']) : NULL;
		
		if ($format)
		{
			if ($timeZone)
				return \DateTime::createFromFormat($format, $value, $timeZone);
			else
				return \DateTime::createFromFormat($format, $value);
		}
		else
		{
			if ($timeZone)
				return new \DateTime($value, $timeZone);
			else
				return new \DateTime($value);
		}
	}
	
}
