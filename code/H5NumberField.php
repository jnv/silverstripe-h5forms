<?php

/**
 * Input type number with min and max support.
 * Based on NumericField, with some retained strings and client-side validation.
 *
 * @todo min/max specification in constructor?
 */
class H5NumberField extends H5InputField
{

	protected $inputType = 'number';
	protected $min, $max;

	function setMin($min)
	{
		$this->min = $min;
	}

	function setMax($max)
	{
		$this->max = $max;
	}

	function Field()
	{
		if($this->min) $this->setAttribute('min', $this->min);
		if($this->max) $this->setAttribute('max', $this->max);

		$html = parent::Field();
		Requirements::javascript(SAPPHIRE_DIR . 'javascript/NumericField.js');

		return $html;
	}

	function jsValidation()
	{
		$formID = $this->form->FormName();
		$error = _t('NumericField.VALIDATIONJS', 'is not a number, only numbers can be accepted for this field');
		$jsFunc = <<<JS
Behaviour.register({
	"#$formID": {
		validateNumericField: function(fieldName) {	
				el = _CURRENT_FORM.elements[fieldName];
				if(!el || !el.value) return true;
				
			 	if(el.value.match(/^\s*(\-?[0-9]+(\.[0-9]+)?\s*$)/)) { 
			 		return true;
			 	} else {
					validationError(el, "'" + el.value + "' $error","validation");
			 		return false;
			 	}
			}
	}
});
JS;

		Requirements::customScript($jsFunc, 'func_validateNumericField');

		//return "\$('$formID').validateNumericField('$this->name');";
		return <<<JS
if(typeof fromAnOnBlur != 'undefined'){
	if(fromAnOnBlur.name == '$this->name')
		$('$formID').validateNumericField('$this->name');
}else{
	$('$formID').validateNumericField('$this->name');
}
JS;
	}

	/** PHP Validation * */
	function validate($validator)
	{
		if($this->value)
		{
			if(is_numeric(trim($this->value)))
			{
				$state = true;
				$msg = 'Number %s ';
				$type = 'H5NumberField.RANGE_';
				$limit = NULL;
				if($this->min && ($this->value < $this->min))
				{
					$state = false;
					$msg.= 'is lower than %s.';
					$type.= 'LOW';
					$limit = $this->min;
				}
				if($state && $this->max && ($this->value > $this->max))
				{
					$state = false;
					$msg.= 'is higher than %s.';
					$type.= 'HIGH';
					$limit = $this->max;
				}
				
				if(!$state)
				{
					
					$validator->validationError(
							$this->name,
							sprintf(
								_t($type, $message),
								$this->value,
								$limit
							),
							'range'
							);
				}
				return $state;
				
			}
			else
			{
				$validator->validationError(
						$this->name,
						sprintf(
								_t('NumericField.VALIDATION', "'%s' is not a number, only numbers can be accepted for this field"),
								$this->value
						),
						"validation"
				);
				return false;
			}
		}
		else
		{
			return true;
		}
	}

	function dataValue()
	{
		return (is_numeric($this->value)) ? $this->value : 0;
	}

}
?>