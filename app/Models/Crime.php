<?php

namespace App\Models;

use App\Models\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class Crime extends Model
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
        'title', 'chance', 'max_chance', 'min_payout', 'max_payout',
    ];

    /**
     * Get the user chance for succeeding a crime.
     *
     * @return float
     */
    public function userChance()
    {
        $chance = floor(user()->info->crime_progress / $this->chance);

        return $chance > $this->max_chance ? $this->max_chance : $chance;
    }
}
