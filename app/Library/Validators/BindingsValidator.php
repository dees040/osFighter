<?php

namespace App\Library\Validators;

use App\Models\Route;
use App\Models\ShoutBox;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\File;

class BindingsValidator
{
    /**
     * Create new validator for checking if the field is
     * has all the route bindings.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @return bool
     */
    public function route($attribute, $value, $parameters)
    {
        $route = Route::findOrFail(head($parameters));

        if (! $route->rules->hasBindings()) {
            return true;
        }

        foreach ($route->rules->bindings() as $binding) {
            if (! str_contains($value, '{'.$binding.'}')) {
                return false;
            }
        }

        return true;
    }
}