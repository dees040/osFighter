<?php

namespace App\Library;

use App\Models\Configuration;
use Illuminate\Support\Collection;
use App\Exceptions\GameConfigNotFound;

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

        foreach (Configuration::all() as $item) {
            $config[$item->key] = $item->value;
        }

        return collect($config);
    }

    /**
     * Get a config value from the game config.
     *
     * @param $configKey
     * @return string
     * @throws GameConfigNotFound
     */
    public function __get($configKey)
    {
        if ($this->config->has($configKey)) {
            return $this->config->get($configKey);
        }

        throw new GameConfigNotFound(sprintf("'%s' is not found in the game config.", $configKey));
    }
}