<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
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
        'group_id', 'permissionable_id', 'permissionable_type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get all of the owning commentable models.
     */
    public function permissionable()
    {
        return $this->morphTo();
    }
}
