<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Latitude field validation rule
 *
 * @package App\Rules
 */
class Latitude implements Rule
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
        $valid = preg_match('/-?\d{1,3}\.\d{4,}/', $value) === 1;

        return $valid && ($value < 90 && $value > -90);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be valid latitude.';
    }

}
