<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use Imageable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'power', 'min_strength_points', 'max_amount'
    ];
}
