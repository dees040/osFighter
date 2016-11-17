<?php

namespace App\Library\Validators;

use App\Models\Route;
use App\Models\ShoutBox;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\File;

class ShoutBoxValidator
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
    public function limit($attribute, $value, $parameters)
    {
        if (user()->isInAdminGroup()) {
            return true;
        }

        $lastTwo = ShoutBox::orderBy('created_at', 'desc')->limit(2)->get();
        $validatedAsNotValid = 0;

        foreach ($lastTwo as $message) {
            if ($message->user_id == currentUser()->id) {
                $validatedAsNotValid++;
            }
        }

        if ($validatedAsNotValid == 2) {
            return false;
        }

        return true;
    }
}