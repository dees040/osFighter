<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChangeAppNameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-name {name : The new application name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Next, we will replace the application name in the environment file so it is
        // automatically setup for this developer.
        $this->setKeyInEnvironmentFile($name);

        $this->laravel['config']['app.name'] = $name;

        $this->info("Application name [$name] set successfully.");
    }

    /**
     * Set the application name in the environment file.
     *
     * @param  string  $key
     * @return void
     */
    protected function setKeyInEnvironmentFile($key)
    {
        file_put_contents($this->laravel->environmentFilePath(), str_replace(
            'APP_NAME='.$this->laravel['config']['app.name'],
            'APP_NAME='.$key,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }
}
