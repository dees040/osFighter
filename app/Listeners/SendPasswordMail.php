<?php

namespace App\Listeners;

use App\Events\AdminCreatedUser;
use App\Models\User;
use App\Notifications\UserWithNewAccount;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class SendPasswordMail
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
     * @param  AdminCreatedUser $event
     * @return void
     */
    public function handle(AdminCreatedUser $event)
    {
        $token = $this->assignTokenToUser($event->user, $this->createToken());

        $event->user->notify(new UserWithNewAccount($token));
    }

    /**
     * Create a new token.
     *
     * @return string
     */
    private function createToken()
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $token = hash_hmac('sha256', Str::random(40), $key);

        return $token;
    }

    /**
     * Assign token to user.
     *
     * @param User $user
     * @param string $token
     * @return string
     */
    private function assignTokenToUser($user, $token)
    {
        \DB::table(config('auth.passwords.users.table'))->insert([
            'email'      => $user->getEmailForPasswordReset(),
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }
}
