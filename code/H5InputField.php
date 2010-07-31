<?php

/**
 * Input field with custom type support for HTML5 fields.
 */
class H5InputField extends H5FormField
{

	/**
	 * @var String
	 */
	protected $inputType = 'text';

	/**
	 * Returns an input field, class="text" and type="text" with an optional maxlength
	 */
	function __construct($name, $title = null, $value = "", $type = '', $maxLength = null, $form = null)
	{

		if(!empty($type)) $this->inputType = $type;

		parent::__construct($name, $title, $value, $maxLength, $form);

		$this->addExtraClass('text');
	}

	/**
	 * @param String $type
	 */
	function setType($type)
	{
		$this->$inputType = $type;
	}

	/**
	 * @return String
	 */
	function getType()
	{
		return $this->inputType;
	}

	function Field()
	{
		$localAtts = array(
			'type' => $this->inputType,
			'class' => $this->inputType . ($this->extraClass() ? $this->extraClass() : ''),
			'id' => $this->id(),
			'name' => $this->Name(),
			'value' => $this->Value(),
			'tabindex' => $this->getTabIndex(),
			'maxlength' => ($this->maxLength) ? $this->maxLength : null,
			'size' => ($this->maxLength) ? min($this->maxLength, 30) : null
		);

		if($this->disabled) $localAtts['disabled'] = 'disabled';

		return $this->createTag('input', $localAtts);
	}

}
?>

