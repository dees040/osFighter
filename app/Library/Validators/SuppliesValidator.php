<?php

namespace App\Library\Validators;

use App\Models\Route;
use App\Models\ShoutBox;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\File;

class SuppliesValidator
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
    public function hasCash($attribute, $value, $parameters)
    {
        return user()->hasSupplies('cash', head($parameters));
    }
}