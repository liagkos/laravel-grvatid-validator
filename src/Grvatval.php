<?php
namespace Liagkos\Grvatval;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

/**
 * Class Grvatval
 * @package Liagkos\Grvatval
 */
class Grvatval implements Rule
{
    /**
     * @param      $attribute   string Form attribute
     * @param      $value       string VAT ID
     * @param null $params
     *
     * @return bool
     */
    public function validate($attribute, $value, $params = null)
    {
        return $this->passes($attribute, $value);
    }

    /**
     * @param $attribute    string Form attribute
     * @param $value        string VAT ID
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // No need for 'numeric' validation rule
        if (strlen($value) != 9 || !is_numeric($value)) {
            return false;
        }

        return $this->checkMod($value);
    }

    /**
     * Make the actual check
     *
     * a. Get the first 8 digits
     * b. Calculate sum of product digit * 2^(8-digit index[0..8])
     * c. Calculate sum mod11 mod10
     * d. Result must be the same as last (9th) digit
     *
     * @param $value    string VAT ID
     *
     * @return bool
     */
    private function checkMod($value)
    {
        $digits = str_split(substr($value, 0, -1));
        $sum    = 0;
        foreach ($digits as $index => $digit) {
            $sum += $digit * pow(2, 8 - $index);
        }
        return $sum % 11 % 10 == (int) $value[8];
    }

    /**
     * @return mixed
     */
    public function message()
    {
        return Lang::get('validation.grvatval');
    }
}