<?php

/**
 * Modified subclass of FormField.
 * Supports extending attributes through setAttribute().
 * Extra attributes are taken into account in createTag().
 * If the subclassed tag doesn't use this function, it should take care of these attributes in Field() function.
 * 
 * @package forms
 * @subpackage core
 */
class H5FormField extends FormField
{

	/**
	 * @var $attributest Extra field attributes for merging, e.g. in Field()
	 */
	protected $attributes = array();

	/**
	 * Construct and return HTML tag.
	 * 
	 * @todo Transform to static helper method.
	 * @todo More proper check whether the tag is a formfield (and required should be given to it)
	 * @todo Prevent repetition of construction of common attributes (class, id, name) in subclasses
	 */
	function createTag($tag, $attributes, $content = null)
	{
		$preparedAttributes = '';

		if($tag != 'span' && $this->Required())
				$this->setAttribute('required', 'required');

		$attributes = array_merge($attributes, $this->attributes);
		foreach($attributes as $k => $v)
		{
			// Note: as indicated by the $k == value item here; the decisions over what to include in the attributes can sometimes get finicky
			if(!empty($v) || $v === '0' || $k == 'value')
					$preparedAttributes .= " $k=\"" . Convert::raw2att($v) . "\"";
		}

		if($content || $tag != 'input')
				return "<$tag$preparedAttributes>$content</$tag>";
		else return "<$tag$preparedAttributes />";
	}

	/**
	 * Sets attribute $key to $value
	 * @param Mixed $key Can be Array or String
	 * @param String $value
	 */
	function setAttribute($key, $value = NULL)
	{
		if(is_array($key))
		{
			foreach($key as $k => $v) setAttribute($k, $v);
			return;
		}

		// Supports boolean attributes, e. g. required="required"
		$this->attributes[$key] = empty($value) ? $key : $value;
	}

	function getAttribute($key)
	{
		if(!array_key_exists($key, $this->attributes)) return NULL;

		return $this->attributes[$key];
	}

}
?>