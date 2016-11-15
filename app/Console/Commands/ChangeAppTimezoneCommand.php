<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChangeAppTimezoneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timezone {timezone : The new application timezone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application timezone';

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
        $timezone = $this->argument('timezone');

        // Next, we will replace the application timezone in the environment file so it is
        // automatically setup for this developer.
        $this->setKeyInEnvironmentFile($timezone);

        $this->laravel['config']['app.timezone'] = $timezone;

        $this->info("Application timezone [$timezone] set successfully.");
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
            'APP_TIMEZONE='.$this->laravel['config']['app.timezone'],
            'APP_TIMEZONE='.$key,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }
}
