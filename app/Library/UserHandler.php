<?php

namespace App\Library;

use App\Models\Car;
use App\Models\Family;
use App\Models\Location;
use App\Models\ShopItem;
use Carbon\Carbon;
use App\Models\Rank;

class UserHandler
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\UserInfo
     */
    public $info;

    /**
     * @var \App\Models\Time
     */
    public $times;

    /**
     * UserHandler constructor.
     *
     * @param null $user
     */
    public function __construct($user = null)
    {
        $user = $user ?: currentUser()->load('info.rank', 'time');

        $this->user = $user;
        $this->info = $user->info;
        $this->times = $user->time;
    }

    /**
     * Add values to user it's info.
     *
     * @param string|array $values
     * @param string $value
     * @return UserHandler
     */
    public function add($values, $value = null)
    {
        return $this->updateValues($values, $value);
    }

    /**
     * Extract values from user it's info.
     *
     * @param string|array $values
     * @param string $value
     * @return UserHandler
     */
    public function take($values, $value = null)
    {
        return $this->updateValues($values, $value, 'take');
    }

    /**
     * Update the rank progress.
     *
     * @param $progress
     * @return UserHandler
     */
    public function addRankProgress($progress)
    {
        if ($this->hasHighestRank()) {
            if ($this->info->progress < 100) {
                if ($this->info->rank_progress + $progress > 100) {
                    $this->update('rank_progress', 100);
                } else {
                    $this->updateValues('rank_progress', $progress);
                }
            }

            return $this;
        }

        $this->updateValues('rank_progress', $progress);

        if ($this->info->rank_progress >= 100) {
            $this->update([
                'rank_id'       => $this->info->rank_id + 1,
                'rank_progress' => $this->info->rank_progress == 100 ? 1 : $this->info->rank_progress - 100,
            ]);
        }

        return $this;
    }

    /**
     * Assign random car to user.
     *
     * @return Car
     */
    public function addRandomCar()
    {
        $car = Car::all()->random();

        $this->user->cars()->attach($car, ['damage' => mt_rand(5, 95)]);

        return $this->user->cars()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Indicates if the user has enough supplies of the given amount.
     *
     * @param string $field
     * @param int $requiredAmount
     * @return bool
     */
    public function hasSupplies($field, $requiredAmount)
    {
        $realAmount = $this->info->$field;

        if (is_null($realAmount)) {
            return false;
        }

        return $realAmount >= $requiredAmount;
    }

    /**
     * Indicates if the user has the highest rank.
     *
     * @return bool
     */
    public function hasHighestRank()
    {
        return count(game()->getRanks()) == $this->info->rank_id;
    }

    /**
     * Indicate is the user is in the jail.
     *
     * @return bool
     */
    public function isInJail()
    {
        return ! $this->mayView('jail');
    }

    /**
     * Indicates if user may view sometime based on the
     * time field given and the current/given time.
     *
     * @param $field
     * @param null $time
     * @return bool
     */
    public function mayView($field, $time = null)
    {
        $time = $time ?: Carbon::now();

        if (is_null($this->times->$field)) {
            return true;
        }

        return $time->gt($this->times->$field);
    }

    /**
     * Check if the user is in the admin group.
     *
     * @return bool
     */
    public function isInAdminGroup()
    {
        return game()->isInAdminGroup();
    }

    /**
     * Indicates if the user is flying.
     *
     * @return bool
     */
    public function isFlying()
    {
        return ! $this->mayView('flying');
    }

    /**
     * Indicate if the user is in a family.
     *
     * @return boolean
     */
    public function isInFamily()
    {
        return ! is_null($this->user->family);
    }

    /**
     * Indicate if the user is in Amsterdam.
     *
     * @return bool
     */
    public function isInAmsterdam()
    {
        return strtolower($this->location()->name) == 'amsterdam';
    }

    /**
     * Get the user it's rank.
     *
     * @return Rank
     */
    public function rank()
    {
        return $this->info->rank;
    }

    /**
     * Get the user location.
     *
     * @return Location
     */
    public function location()
    {
        return $this->info->location;
    }

    /**
     * Get the user it's family.
     *
     * @return Family|null
     */
    public function family()
    {
        return $this->user->family;
    }

    /**
     * Get the amount of cash earned by the user it's hoes.
     *
     * @return float
     */
    public function getCashAmountFromHoes()
    {
        $hoursPast = floor(sec_difference($this->times->pimped_cash) / 3600);

        return $hoursPast * 60;
    }

    /**
     * Get the user pivot shop item model.
     *
     * @param ShopItem $item
     * @return ShopItem|null
     */
    public function getShopItem(ShopItem $item)
    {
        return $this->user->shopItems->where('id', $item->id)->first();
    }

    /**
     * Update one or multiple values in the info table.
     *
     * @param string|array $key
     * @param string $value
     * @return UserHandler
     */
    public function update($key, $value = null)
    {
        $values = is_array($key) ? $key : [$key => $value];

        $this->info->update($values);

        return $this;
    }

    /**
     * Update a user time.
     *
     * @param string|array $field
     * @param Carbon $timestamp
     * @return UserHandler
     */
    public function updateTime($field, $timestamp = null)
    {
        if (! is_array($field)) {
            $field = [$field => $timestamp];
        }

        $this->times->update($field);

        return $this;
    }

    /**
     * Update given values.
     *
     * @param $values
     * @param $value
     * @param string $operation
     * @return UserHandler
     */
    private function updateValues($values, $value, $operation = 'add')
    {
        if (! is_array($values)) {
            $values = [$values => $value];
        }

        foreach ($values as $key => $value) {
            call_user_func_array([$this, $operation . 'Value'], [$key, $value]);
        }

        $this->info->save();

        return $this;
    }

    /**
     * Add value to given key.
     *
     * @param $key
     * @param $value
     */
    protected function addValue($key, $value)
    {
        $this->info->$key = $this->info->$key + $value;
    }

    /**
     * Extract value from given key.
     *
     * @param $key
     * @param $value
     */
    protected function takeValue($key, $value)
    {
        $this->info->$key = $this->info->$key - $value;
    }

    /**
     * Get the user model.
     *
     * @return \App\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get a field.
     *
     * @param $field
     * @return mixed
     */
    public function __get($field)
    {
        $models = ['user', 'info', 'times'];

        foreach ($models as $model) {
            $value = $this->$model->getAttributeValue($field);

            if (! is_null($value)) {
                return $value;
            }
        }

        return "";
    }
}