<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
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
        'name', 'price'
    ];
}
