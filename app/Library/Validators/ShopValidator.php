<?php

namespace App\Library\Validators;

use App\Models\Route;
use App\Models\ShopItem;
use App\Models\ShoutBox;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\File;

class ShopValidator
{
    /**
     * Create new validator for checking if the user has
     * the required strength points.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @return bool
     */
    public function strengthPoints($attribute, $value, $parameters)
    {
        $item = ShopItem::find(head($parameters));

        if ($item->min_strength_points == 0) {
            return true;
        }

        return $item->min_strength_points < user()->strength;
    }

    /**
     * Create new validator for checking if the field doesn't
     * override the maximum amount of a shop item.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @return bool
     */
    public function amount($attribute, $value, $parameters)
    {
        $item = ShopItem::find(head($parameters));

        if ($item->max_amount == 0) {
            return true;
        }

        $userItem = user()->getShopItem($item);

        if (is_null($userItem)) {
            return intval($value) < $item->max_amount;
        }

        return intval($userItem->pivot->amount) + intval($value) < $item->max_amount;
    }
}