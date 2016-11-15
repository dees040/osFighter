<?php

namespace App\Library\Validators;

use App\Models\Page;
use Symfony\Component\HttpFoundation\File\File;

class RequirementValidator
{
    /**
     * Create new validator for checking if the field is
     * required and only if it's menuable.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @return bool
     */
    public function ifMenuable($attribute, $value, $parameters)
    {
        $page = Page::findOrFail(head($parameters));

        if ($page->menuable) {
            return $this->validateRequired($value);
        }

        return true;
    }

    /**
     * Validate if the value is required.
     *
     * @param $value
     * @return bool
     */
    private function validateRequired($value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1) {
            return false;
        } elseif ($value instanceof File) {
            return (string) $value->getPath() != '';
        }

        return true;
    }
}