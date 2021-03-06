<?php

namespace Vinelab\Minion;

use Illuminate\Support\ServiceProvider;
use Vinelab\Minion\Console\Commands\RunCommand;

/**
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class MinionServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/minion.php' => config_path('minion.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Setup the facade alias
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Minion', 'Vinelab\Minion\Facade\Minion');
        });

        // add the minion class and command to the app under the vinelab namespace
        $this->app->singleton('vinelab.minion', 'Vinelab\Minion\Minion');
        $this->app->singleton('vinelab.minion.run', function () {
            $command = new RunCommand();
            $command->isLaravel = true;
            $command->setName('minion:run');

            return $command;
        });

        $this->commands('vinelab.minion.run');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
