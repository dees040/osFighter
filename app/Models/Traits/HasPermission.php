<?php

namespace App\Models\Traits;

use App\Models\Permission;

trait HasPermission
{
    /**
     * Use this if there is only one permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function permission()
    {
        return $this->morphOne(Permission::class, 'permissionable');
    }
}