<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GameTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function it_does_not_allow_user_to_visit_unauthorized_pages()
    {
        $userNotAuthorised = factory(\App\Models\User::class)->create(['group_id' => 1]);
        $page = factory(\App\Models\Route::class)->create(['group_id' => 2]);

        $this->actingAs($userNotAuthorised);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $boolean = game()->abortUnlessHasPermissionForPage($page);

        $this->assertFalse($boolean);
    }

    /** @test */
    public function it_allows_user_to_visit_page_if_authorized()
    {
        $userAuthorised = factory(\App\Models\User::class)->create(['group_id' => 4]);
        $page = factory(\App\Models\Route::class)->create(['group_id' => 2]);

        $this->actingAs($userAuthorised);

        $boolean = game()->abortUnlessHasPermissionForPage($page);

        $this->assertTrue($boolean);
    }
    
    /** @test */
    public function it_gives_the_correct_payout_for_a_crime()
    {
        $minPayout = mt_rand(10, 100);
        $maxPayout = mt_rand(101, 1000);

        $crime = factory(\App\Models\Crime::class)->create(['min_payout' => $minPayout, 'max_payout' => $maxPayout]);

        $payout = game()->crimePayout($crime);

        $this->assertGreaterThanOrEqual($minPayout, $payout);
        $this->assertLessThanOrEqual($maxPayout, $payout);
    }

    /** @test */
    public function it_knows_if_a_user_is_in_jail()
    {
        $user = factory(\App\Models\User::class)->create();
        $page = factory(\App\Models\Route::class)->create(['jail' => 1]);

        $this->actingAs($user);

        user()->updateTime('jail', \Carbon\Carbon::now()->addHour());

        $this->assertTrue(game()->userNeedsToGoToJail($page));
    }

    /** @test */
    public function it_knows_the_admin_group()
    {
        $group = game()->getAdminGroup();

        $this->assertInstanceOf(\App\Models\Group::class, $group);
        $this->assertEquals($group->id, 4);
    }
}
