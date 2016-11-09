<?php

namespace App\Library;

use Carbon\Carbon;

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
     */
    public function __construct()
    {
        $user = currentUser()->load('info', 'time');

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
        $this->updateValues($values, $value, false);
    }

    /**
     * Update a user time.
     *
     * @param $field
     * @param $time
     */
    public function updateTime($field, Carbon $time)
    {
        if (property_exists($field, $this->times)) {
            $this->times->update([
                $field => $time,
            ]);
        }
    }

    /**
     * Update given values.
     *
     * @param $values
     * @param $value
     * @param bool $add
     */
    private function updateValues($values, $value, $add = true)
    {
        if (! is_array($values)) {
            $values = [$values => $value];
        }

        foreach ($values as $key => $value) {
            if ($add) {
                $this->addValue($key, $value);
            } else {
                $this->extractValue($key, $value);
            }
        }

        $this->info->save();
    }

    /**
     * Add value to given key.
     *
     * @param $key
     * @param $value
     */
    private function addValue($key, $value)
    {
        $this->info->$key = $this->info->$key + $value;
    }

    /**
     * Extract value from given key.
     *
     * @param $key
     * @param $value
     */
    private function extractValue($key, $value)
    {
        $this->info->$key = $this->info->$key - $value;
    }
}