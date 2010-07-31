<?php

/**
 * H5TextareaField, only to get required and custom attributes support.
 * Otherwise
 * @todo wrap attribute support
 */
class H5TextareaField extends H5FormField
{

	protected $rows, $cols, $disabled = false, $readonly = false;

	/**
	 * Create a new textarea field.
	 * 
	 * @param $name Field name
	 * @param $title Field title
	 * @param $rows The number of rows
	 * @param $cols The number of columns
	 * @param $value The current value
	 * @param $form The parent form.  Auto-set when the field is placed in a form.
	 */
	function __construct($name, $title = null, $rows = 5, $cols = 20, $value = "", $form = null)
	{
		$this->rows = $rows;
		$this->cols = $cols;
		parent::__construct($name, $title, $value, $form);
	}

	/**
	 * Create the <textarea> or <span> HTML tag with the
	 * attributes for this instance of TextareaField. This
	 * makes use of {@link FormField->createTag()} functionality.
	 * 
	 * @return HTML code for the textarea OR span element
	 */
	function Field()
	{
		$class = ($this->extraClass() ? $this->extraClass() : '');

		$attributes = array(
			'id' => $this->id(),
			'name' => $this->name,
			'class' => $class
		);

		$value = htmlentities($this->value, ENT_COMPAT, 'UTF-8');

		if($this->readonly)
		{
			$attributes['readonly'] = 'readonly';
			$attributes['class'].= ' readonly';

			return $this->createTag(
					'span',
					$attributes,
					(empty($value) ? '<i>(' . _t('FormField.NONE', 'none') . ')</i>' : $value)
			);
		}
		else
		{
			$attributes['tabindex'] = $this->getTabIndex();
			$attributes['rows'] = $this->rows;
			$attributes['cols'] = $this->cols;

			if($this->disabled) $attributes['disabled'] = 'disabled';

			return $this->createTag('textarea', $attributes, $value);
		}
	}

	/**
	 * Performs a readonly transformation on this field. You should still be able
	 * to copy from this field, and it should still send when you submit
	 * the form it's attached to.
	 * The element shouldn't be both disabled and readonly at the same time.
	 */
	function performReadonlyTransformation()
	{
		$clone = clone $this;
		$clone->setReadonly(true);
		$clone->setDisabled(false);
		return $clone;
	}

	/**
	 * Performs a disabled transformation on this field. You shouldn't be able to
	 * copy from this field, and it should not send any data when you submit the 
	 * form it's attached to.
	 * The element shouldn't be both disabled and readonly at the same time.
	 */
	function performDisabledTransformation()
	{
		$clone = clone $this;
		$clone->setDisabled(true);
		$clone->setReadonly(false);
		return $clone;
	}

	function Type()
	{
		return parent::Type() . ( $this->readonly ? ' readonly' : '' );
	}

	/**
	 * Set the number of rows in the textarea
	 *
	 * @param int
	 */
	function setRows($rows)
	{
		$this->rows = $rows;
	}

	/**
	 * Set the number of columns in the textarea
	 *
	 * @return int
	 */
	function setColumns($cols)
	{
		$this->cols = $cols;
	}

}