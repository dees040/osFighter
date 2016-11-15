<?php

namespace App\Library;

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
     */
    public function add($values, $value = null)
    {
        $this->updateValues($values, $value);
    }

    /**
     * Extract values from user it's info.
     *
     * @param string|array $values
     * @param string $value
     */
    public function extract($values, $value = null)
    {
        $this->updateValues($values, $value, 'extract');
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
     * Get the user it's rank.
     *
     * @return string
     */
    public function rank()
    {
        $rank = game()->getRanks($this->info->rank);

        if (is_null($rank) || ! $rank instanceof Rank) {
            return "";
        }

        return $rank->name;
    }

    /**
     * Update a user time.
     *
     * @param string|array $field
     * @param Carbon $timestamp
     */
    public function updateTime($field, $timestamp = null)
    {
        if (! is_array($field)) {
            $field = [$field => $timestamp];
        }

        $this->times->update($field);
    }

    /**
     * Update given values.
     *
     * @param $values
     * @param $value
     * @param string $operation
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
    protected function extractValue($key, $value)
    {
        $this->info->$key = $this->info->$key - $value;
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