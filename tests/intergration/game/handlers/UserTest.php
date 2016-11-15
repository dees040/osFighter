<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function it_can_edit_data()
    {
        $user = user(factory(\App\Models\User::class)->create());

        $user->add(['cash' => 100, 'bank' => 100]);
        $user->extract(['cash' => 20, 'bank' => 10]);

        $this->assertEquals($user->cash, 80);
        $this->assertEquals($user->bank, 90);
    }

    /** @test */
    public function it_can_update_time()
    {
        $user = user(factory(\App\Models\User::class)->create());

        $nextHour = \Carbon\Carbon::now()->addHour();

        $user->updateTime('jail', $nextHour);

        $this->assertEquals($user->jail->getTimestamp(), $nextHour->getTimestamp());
    }

    /** @test */
    public function it_can_update_time_formatted_as_array()
    {
        $user = user(factory(\App\Models\User::class)->create());

        $nextHour = \Carbon\Carbon::now()->addHour();

        $user->updateTime(['jail' => $nextHour, 'crime' => $nextHour]);

        $this->assertEquals($user->jail->getTimestamp(), $nextHour->getTimestamp());
        $this->assertEquals($user->crime->getTimestamp(), $nextHour->getTimestamp());
    }

    /** @test */
    public function it_knows_the_user_rank()
    {
        $user = user(factory(\App\Models\User::class)->create());
        $rank = game()->getRanks()->first();

        $this->assertEquals($rank->name, $user->rank());
    }
}