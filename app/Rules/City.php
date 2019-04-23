<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * City filed validation rule
 *
 * @package App\Rules
 */
class City implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (intval($value) > 0) {
            return false;
        }

        if (preg_match('/^[\w\-\s]+$/', $value) !== 1) {
            return false;
        }

        return ucfirst($value) === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid city name.';
    }

}
