<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Model;

class Crime extends Model
{
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
     * Boot the model.
     */
    protected static function boot() {
        parent::boot();

        // Destroy the crime image before deleting.
        static::deleting(function(Crime $crime) {
            $crime->deleteImage();
        });
    }

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

    /**
     * Delete the image associated with the crime.
     */
    public function deleteImage()
    {
        File::delete(public_path('images/game/crimes/' . $this->id . '.jpg'));
    }

    /**
     * Get the path/url to the crime image.
     *
     * @return string
     */
    public function image()
    {
        return asset('images/game/crimes/' . $this->id . '.jpg');
    }
}
