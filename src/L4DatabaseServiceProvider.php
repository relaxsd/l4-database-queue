<?php namespace Relaxsd\Queue;

use Illuminate\Support\ServiceProvider;
use Relaxsd\Queue\Connectors\DatabaseConnector;
use Relaxsd\Queue\Console\TableCommand;

class L4DatabaseServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerQueueTableCommand();

        $this->registerDatabaseConnector($this->app['queue']);

    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueTableCommand()
    {
        $this->app->singleton('command.queue.table', function ($app) {
            return new TableCommand($app['files'], $app['composer']);
        });

        $this->command('command.queue.table');
    }

    /**
     * Register the database queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager $manager
     * @return void
     */
    protected function registerDatabaseConnector($manager)
    {
        $manager->addConnector('database', function () {
            return new DatabaseConnector($this->app['db']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.queue.table'];
    }
}