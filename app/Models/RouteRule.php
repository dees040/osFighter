<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class RouteRule extends Model
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
        'route_id', 'group_id', 'menuable', 'jail_viewable', 'fly_viewable', 'family_viewable', 'bindings',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'menuable'        => 'boolean',
        'jail_viewable'   => 'boolean',
        'fly_viewable'    => 'boolean',
        'family_viewable' => 'boolean',
    ];

    /**
     * Indicate is the route rule has route parameter bindings.
     *
     * @return bool
     */
    public function hasBindings()
    {
        return ! is_null($this->bindings) && $this->bindings;
    }

    /**
     * Get an array with the route bindings.
     *
     * @return Collection
     */
    public function bindings()
    {
        return collect(explode('|', $this->bindings));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
