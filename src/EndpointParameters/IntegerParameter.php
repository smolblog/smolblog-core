<?php

namespace Smolblog\Core\EndpointParameters;

use Smolblog\Core\EndpointParameter;

/**
 * EndpointParameter that accepts and validates an integer
 */
class IntegerParameter extends EndpointParameter {
	/**
	 * Validate that the given value _can_ be an integer
	 *
	 * @param mixed $given_value Value of this parameter as given in the request.
	 * @return boolean true if this is a valid value.
	 */
	protected function extendedValidation(mixed $given_value = null): bool {
		return is_numeric($given_value);
	}

	/**
	 * Turn the given value into an integer
	 *
	 * @param mixed $given_value Value of this parameter as given in the request.
	 * @return integer Parsed value of the parameter.
	 */
	protected function extendedParsing(mixed $given_value = null): int {
		return intval($given_value);
	}
}
