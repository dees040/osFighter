<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoutBox extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shoutbox';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'body',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
