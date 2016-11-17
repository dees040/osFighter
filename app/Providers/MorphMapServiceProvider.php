<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mapRelations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Map the morph relations.
     */
    protected function mapRelations()
    {
        Relation::morphMap([
            'groups' => \App\Models\Group::class,
            'pages' => \App\Models\Route::class,
        ]);
    }
}
