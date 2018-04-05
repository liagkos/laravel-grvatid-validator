# Greek VAT ID Validator for Laravel

The Greek VAT ID validator checks if a number is POTENTIALLY valid in Greek VAT ID Registry.

Validator does NOT check if VAT ID does actually belong to a person or a company, it checks only if it obeys the algorithm of a valid Greek VAT ID:

```
1. VAT ID = 090000045
2. Calculate sum of 0*2^8+9*2^7+0*2^6+0*2^5+0*2^4+0*2^3+0*2^2+4*2^1 = 1160
3. Calculate mod11 = 1160 % 11 = 5
4. Calculate mod10 = 5 % 10 = 5
5. mod10 (5) is equal to 5 (last digit), so VAT ID is VALID
```

```
1. VAT ID = 123456789
2. Calculate sum of 1*2^8+2*2^7+3*2^6+4*2^5+5*2^4+6*2^3+7*2^2+8*2^1 = 1004
3. Calculate mod11 = 1004 % 11 = 3
4. Calculate mod10 = 3 % 10 = 3
5. mod10 (3) is not equal to 9 (last digit), so VAT ID is INVALID
```

## Installation

Install the package using Composer:

```
composer require liagkos/laravel-grvatid-validator
```

Laravel's service provider discovery will automatically configure the Grvatval service provider for you.

## Using the `grvatval` validator

After installation, the `grvatval` validator will be available for use directly in your validation rules.
```php
'vatid' => 'grvatval',
```

Within the context of a registration form, it would look like this:
```php
return Validator::make($data, [
    'name' => 'required',
    'email' => 'required|string|email',
    'password' => 'required',
    'vatid' => 'required|grvatval'
]);
```

## Using the Rule Object

Alternatively, you can use the `Liagkos\Grvatval\Grvatval` [Validation Rule Object](https://laravel.com/docs/5.5/validation#using-rule-objects)
instead of the `grvatval` alias if you prefer:

```php
return Validator::make($data, [
    'name' => 'required',
    'email' => 'required|string|email',
    'password' => 'required',
    'vatid' => ['required', new \Liagkos\Grvatval\Grvatval],
]);
```

## Validation message

You will need to assign your own validation message within the `resources/lang/*/validation.php` file(s).
Both the Rule object and the `grvatval` validator alias refer to the validation string `validation.grvatval`.

## Testing

Just run the the test in `tests` directory. I use the [orchestra/testbench](https://github.com/orchestral/testbench) along with PHPUnit.

## Credits

Since I'm quite new in all Laravel stuff, I got the idea from [Stephen Rees-Carten](https://github.com/valorin) and his own [valorin/pwned-validator](https://github.com/valorin/pwned-validator) validator, from whom I used some code and README.md stuff, modified accordingly to make this validator. Thank you Stephen, live long and propsper!
