<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateUserInformation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->info()->create([

        ]);

        $event->user->info()->create([
            'jail'  => Carbon::now(),
            'crime' => Carbon::now(),
        ]);
    }
}
