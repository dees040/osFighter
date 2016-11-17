<?php

namespace App\Library;

use App\Models\Rank;
use App\Models\Time;
use Carbon\Carbon;
use Schema;
use App\Models\Menu;
use App\Models\Route;
use App\Models\User;
use App\Models\Crime;
use App\Models\Group;
use App\Models\Configuration;
use Illuminate\Support\Collection;

class Game
{
    /**
     * @var Collection
     */
    protected $config;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->config = $this->createConfigArray();
    }

    /**
     * Create the config array.
     *
     * @return Collection
     */
    private function createConfigArray()
    {
        $config = [];

        // Small check if the configurations table exists. This is necessary because
        // of the dynamic routes which are loaded from the pages table.
        if (! Schema::hasTable('configurations')) {
            return collect($config);
        }

        foreach (Configuration::all() as $item) {
            $config[$item->key] = $item->value;
        }

        return collect($config);
    }

    /**
     * Get all the application menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function menus()
    {
        return Menu::all();
    }

    /**
     * Get all the application left menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function leftMenus()
    {
        return Menu::with('routes.rules')->where('position', 1)->get();
    }

    /**
     * Get all the application right menus.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function rightMenus()
    {
        return Menu::with('routes.rules')->where('position', 2)->get();
    }

    /**
     * Get all the application pages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pages()
    {
        // Small check if the pages table exists. This is necessary because
        // of the dynamic routes which are loaded from the pages table.
        if (Schema::hasTable('pages')) {
            return Route::all();
        }

        return collect();
    }

    /**
     * Check if the user has permission for a role.
     *
     * @param Group|int $group
     * @param User $user
     * @return bool
     */
    public function hasPermissionForGroup($group, $user = null)
    {
        // If the group is not an instance of Group it is probably a integer.
        // With this integer we need to find to correct group.
        if (! $group instanceof Group) {
            $group = Group::findOrFail($group);
        }

        // Set the user, if needed.
        if (is_null($user)) {
            $user = auth()->user();
        }

        // If the user is in the given group we return true.
        if ($user->group_id == $group->id) {
            return true;
        }

        // If the user is not in the given group but the group has a parent, we
        // need to check if the user does have permission for the parent group.
        if (! is_null($group->parent)) {
            return $this->hasPermissionForGroup($group->parent, $user);
        }

        // The user doesn't have permission for the given group.
        return false;
    }

    /**
     * Abort the request unless the user is in the given group.
     *
     * @param Group|int $group
     * @return bool
     */
    public function abortUnlessIsInGroup($group)
    {
        if ($this->hasPermissionForGroup($group)) {
            return true;
        }

        abort(403);

        return false;
    }

    /**
     * Abort the request unless the user is an admin.
     *
     * @return bool
     */
    public function abortUnlessIsAdmin()
    {
        return $this->abortUnlessIsInGroup($this->getAdminGroup());
    }

    /**
     * Abort unless the current user has permission for the
     * current page.
     *
     * @param null $page
     * @return bool
     */
    public function abortUnlessHasPermissionForPage($page = null)
    {
        $page = $page ?: $this->getCurrentPage();

        // We assume this is a post request for a non dynamic route.
        if (is_null($page)) {
            return true;
        }

        if (is_null($page->group)) {
            abort(403);
        }

        return $this->abortUnlessIsInGroup($page->group);
    }

    /**
     * Check if the user is in the admin group.
     *
     * @return bool
     */
    public function isInAdminGroup()
    {
        return $this->hasPermissionForGroup($this->getAdminGroup());
    }

    /**
     * Indicates if a given group has a parent.
     *
     * @param Group $group
     * @return bool
     */
    public function groupHasParent(Group $group)
    {
        return ! is_null($group->parent);
    }
    
    /**
     * Get a random payout for the given crime.
     *
     * @param Crime $crime
     * @return int
     */
    public function crimePayout(Crime $crime)
    {
        return mt_rand($crime->min_payout, $crime->max_payout);
    }

    /**
     * Check if the user needs to go to jail.
     *
     * @param null|Route $page
     * @return bool
     */
    public function userNeedsToGoToJail($page = null)
    {
        $page = $page ?: $this->getCurrentPage();

        if (is_null($page)) {
            return false;
        }

        return $page->jail && user()->isInJail();
    }

    /**
     * Get all users which are in jail.
     *
     * @return Collection
     */
    public function usersInJail()
    {
        $users = User::whereHas('time', function ($query) {
            $query->where('jail', '>', Carbon::now());
        })->get();

        return $users;
    }
    
    /**
     * Indicate if we need to use captcha.
     *
     * @return bool
     */
    public function isUsingCaptcha()
    {
        return $this->captcha ? true : false;
    }

    /**
     * Get the ranks from the database.
     *
     * @param Rank $rank
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRanks($rank = null)
    {
        $ranks = Rank::all();

        if (is_null($rank)) {
            return $ranks;
        }

        return $ranks->where('id', $rank->id)->first();
    }

    /**
     * Get the admin group.
     *
     * @return Group
     */
    public function getAdminGroup()
    {
        return Group::findOrFail($this->__get('admin_group'));
    }

    /**
     * Get the current Page model.
     *
     * @return Route|mixed
     */
    public function getCurrentPage()
    {
        $name = request()->route()->getName();

        return Route::with('group')
            ->where('route_name', $name)
            ->first();
    }

    /**
     * Get all parents groups from a given from.
     *
     * @param Group $group
     * @return Collection
     */
    public function getAllParentsFromGroup(Group $group)
    {
        $groupList = [$group];

        while ($this->groupHasParent($group)) {
            $groupList[] = $group = $group->parent;
        }

        return collect($groupList)->unique();
    }

    /**
     * Get a config value from the game config.
     *
     * @param $configKey
     * @return string
     */
    public function __get($configKey)
    {
        if ($this->config->has($configKey)) {
            return $this->config->get($configKey);
        }

        //throw new GameConfigNotFound(sprintf("'%s' is not found in the game config.", $configKey));

        return null;
    }
}